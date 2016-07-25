<?php

namespace it\icosaedro\web;

require_once __DIR__ . "/../../../all.php";

/*. require_module 'pcre'; .*/

use RuntimeException;
use ErrorException;
use InvalidArgumentException;

	
/**
 * Allows to start and control an offline working process. Mostly useful in WEB
 * applications to launch background processes that may take an arbitrary long time
 * to complete and, in the meanwhile, allowing to provide feedback to the user about
 * the status of that process, including percentage of completion, textual feedback,
 * exit code, generated standard output and standard error. Every job is univocally
 * identified by a ticket that allows to retrieve its status at any time.
 * 
 * <p><b>Creating and launching a new job</b><br>
 * In order to use this class, you must first create a directory where temporary
 * working sub-directories will be created. The path of this directory must be
 * set in the $jobs_directory static property of this class before any usage.
 * 
 * <p>New jobs are created by the {@link self::create($name)} static method, where $name
 * is the name of that job. This method creates a temporary working directory where
 * the process will be executed. Then the {@link self::start($command)} starts the
 * command to be executed in background. The command is executed using the identity of
 * the current user and the temporary directory of the job as the current working
 * directory. Example:
 * 
 * <blockquote><pre>
 * // Initialize the OfflineJob class:
 * OfflineJob::$jobs_directory = "/var/www/private/jobs";
 * 
 * // Create and start a new job:
 * $job = OfflineJob::create("Executing my program");
 * $job-&gt;start("/home/MyName/myprogram arg1 arg2");
 * 
 * // Retrieve the ticket:
 * $ticket = $job-&gt;getTicket();
 * </pre></blockquote>
 * 
 * 
 * <p><b>Polling for the status of the job</b><br>
 * Later, the status of the job can be polled calling the {@link self::retrieve($ticket)}
 * method with the value of the ticket. This method retrieves an object that
 * represents the current status of the job. Example:
 * 
 * <blockquote><pre>
 * // Initialize the OfflineJob class:
 * OfflineJob::$jobs_directory = "/var/www/private/jobs";
 * 
 * // Retrieve the current status of the job:
 * $job = OfflineJob::retrieve($ticket);
 * 
 * // Send feedback to the user:
 * $progress = $job-&gt;getProgressPercentage();
 * $feedback = $job-&gt;getFeedback();
 * if( $job-&gt;isFinished() )
 *     echo "finished with exit code ", $job-&gt;getExitCode();
 * else if( $progress &gt; 0 )
 *     echo "$progress% done\n$feedback";
 * else
 *     echo "still running";
 * </pre></blockquote>
 * 
 * Several other methods allows to retrieve the execution time, the feedback message,
 * the percentage of completion, the standard output and standard error, etc.
 * Unix signals can be sent to the running process using the {@link self::kill($signal)}
 * method.
 * 
 * <p><b>Tasks the background process should do</b><br>
 * The background process may write the result of its work to the standard output
 * stream, may send errors to the standard error stream, and then should return
 * a final exit code, which will be usually 0 for success and 1 for error.
 * 
 * <p>If the amount of work to do is known in advance, the process may write to the
 * <tt>progress_percentage</tt> file an integer number in the range between 0 and 100,
 * zero being the default indicating an undetermined amount of work, and 100 indicating
 * 100% completion of the work. Note that a process reporting its work is 100%
 * complete may still be running performing finishing an clean-up operations, so
 * the exit code, finishing time and other resulting values are still undetermined
 * or incomplete.
 * 
 * <p>The background process should also be prepared to receive Unix signals from
 * the controller application, the typical signal being a catchable TERM request.
 * The behavior of the process when a termination request is received should be
 * consistent, possibly canceling any modification done so far to the system, or
 * simply stopping the work at the point where it is; an appropriate feedback
 * (something like "Premature end requested, the task may be incomplete.") and
 * possibly error message and error exit code should be set accordingly in these
 * cases.
 * 
 * 
 * <p><b>Implementation notes</b><br>
 * For each offline job, a temporary directory is created; the name of this
 * directory is the ticket that univocally identifies the job. There may be
 * at most one process executing that job.
 * 
 * Under this directory, several files contain the state of the job, including:
 * <p>
 * <tt>name</tt>: human-readable name of this job.<br>
 * <tt>status</tt>: status of the job, see constants below.<br>
 * <tt>created</tt>: timestamp of creation of this job.<br>
 * <tt>pid</tt>: PID of the process executing the job, or 0 if ready but still not started.<br>
 * <tt>started</tt>: timestamp of start of the process, default to 0.<br>
 * <tt>finished</tt>: timestamp of termination of the process, default 0.<br>
 * <tt>command</tt>: command line as issued by the user.<br>
 * <tt>exec_command</tt>: command line as sent to the exec() function; allows to precisely
 * detect the process in the processes table because it also contains the ticket.<br>
 * <tt>stdout</tt>: standard output of the process.<br>
 * <tt>stderr</tt>: standard error of the process.<br>
 * <tt>feedback</tt>: feedback message telling what the process is doing right now<br>
 * <tt>progress_percentage</tt>: the process may write here an int number in the range
 * [0,100] indicating the percentage of work done; if the amount of work to do
 * cannot be determined, this value should be left to its default 0.<br>
 * <tt>exit_code</tt>: exit code of a finished process, default 0.
 * <p>
 * @author Umberto Salsi <salsi@icosaedro.it>
 * @version $Date: 2015/08/20 12:10:30 $
 */
class OfflineJob {
	
	/**
	 * Base directory where jobs' temporary directories are created.
	 * The client must set this parameter before using this class.
	 * @var string 
	 */
	public static $jobs_directory;
	
	/**
	 * Possible statuses of the offline job.
	 * ready: command issued, but pid file still contains the default "0" value.
	 * running: process started and running.
	 * finished: process does not exist anymore.
	 * @access private
	 */
	const STATUS_READY = 0,
		STATUS_RUNNING = 1,
		STATUS_FINISHED = 2;
	
	/**
	 *
	 * @var string
	 */
	private $ticket;
	
	/**
	 *
	 * @var string
	 */
	private $dir;
	
	/**
	 *
	 * @var string
	 */
	private $name;
	
	/**
	 * Shell command as submitted by user.
	 * @var string
	 */
	private $command;
	
	/**
	 * Shell command as submitted to the exec() function.
	 * @var string
	 */
	private $exec_command;
	
	/**
	 * 
	 * @var int
	 */
	private $pid = 0;
	
	private $created = 0;
	private $started = 0;
	private $finished = 0;
	
	private $status = self::STATUS_READY;
	
	/**
	 *
	 * @var int
	 */
	private $progress_percentage = 0;
	
	/**
	 * Feedback message from the running process. Empty by default. Once finished,
	 * it contains the last feedback message. The format, encoding and meaning of
	 * this string is totally dependent on the implementation.
	 * @var string
	 */
	private $feedback;
	
	/**
	 *
	 * @var string
	 */
	private $stdout;
	
	/**
	 *
	 * @var string
	 */
	private $stderr;
	
	private $exit_code = 0;
	
	
	/**
	 * 
	 * @param string $name
	 * @return string
	 * @throws ErrorException
	 */
	private function readProperty($name)
	{
		return file_get_contents($this->dir . "/$name");
	}
	
	
	/**
	 * 
	 * @param string $name
	 * @param string $value
	 * @throws ErrorException
	 */
	private function writeProperty($name, $value)
	{
		file_put_contents($this->dir . "/$name", $value);
	}
	
	
	/**
	 * Returns a string containing the main properties of this job.
	 * @return string
	 */
	function __toString()
	{
		return __CLASS__."["
		."name=".$this->name
		.", command=" .$this->command
		.", status=".$this->status
		.", pid=".$this->pid
		.", exit_code=".$this->exit_code
		.", progress_percentage=".$this->progress_percentage
		."]";
	}
	
	
	/**
	 * @param string $name Human-readable name of this job.
	 * @return self
	 * @throws ErrorException
	 */
	static function create($name)
	{
		$now = time();
		$job = new self();
		$job->ticket = sprintf("%08x%04x%04x", $now - 1400000000, rand(0, 0xffff), rand(0, 0xffff));
		if( self::$jobs_directory === NULL )
			throw new RuntimeException("you must set the ".__CLASS__."::\$jobs_directory property before using this class");
		if( ! file_exists(self::$jobs_directory) )
			throw new RuntimeException("jobs storage directory does not exists: ".self::$jobs_directory);
		$job->dir = self::$jobs_directory . "/" . $job->ticket;
		mkdir($job->dir); // throw ErrorException on dir name collision
		$job->name = $name;  $job->writeProperty("name", $job->name);
		$job->command = "";   $job->writeProperty("command", $job->command);
		$job->exec_command = "";  $job->writeProperty("exec_command", $job->exec_command);
		$job->stdout = "";  $job->writeProperty("stdout", $job->stdout);
		$job->stderr = "";  $job->writeProperty("stderr", $job->stderr);
		$job->status = self::STATUS_READY;  $job->writeProperty("status", "".$job->status);
		$job->pid = 0;  $job->writeProperty("pid", "".$job->pid);
		$job->created = $now;  $job->writeProperty("created", "".$job->created);
		$job->started = 0;  $job->writeProperty("started", "".$job->started);
		$job->finished = 0; $job->writeProperty("finished", "".$job->finished);
		$job->exit_code = 0;  $job->writeProperty("exit_code", "".$job->exit_code);
		$job->progress_percentage = 0;  $job->writeProperty("progress_percentage", "".$job->progress_percentage);
		$job->feedback = "";  $job->writeProperty("feedback", $job->feedback);
		return $job;
	}
	
	
//	private function setLock()
//	{
//		$fn = $this->getPropertyPath("exec_command");
//		$f = fopen($fn, "r");
//		if( ! flock($f, LOCK_EX) )
//			throw new ErrorException("concurrent access to the lock file $fn");
//	}
	
	
	/**
	 * Starts a process under this job. This method must be called just one time
	 * after the create() method to actually start the background process.
	 * @param string $command
	 * @throws ErrorException
	 * @throws InvalidArgumentException Empty command string.
	 * @throws RuntimeException Command already set.
	 */
	public function start($command)
	{
		$command = trim($command);
		if( strlen($command) == 0 )
			throw new InvalidArgumentException("empty command");
		if( strlen($this->command) > 0 )
			throw new RuntimeException("command already set");
		$this->command = $command;  $this->writeProperty("command", $this->command);
		// Builds a shell command that sets the working directory and captures pid,
		// stdout, stderr and exit code of the process; also note that the resulting
		// string contains the ticket, which makes this command string univocal
		// in the processes table and allows to safely recognize our running process.
		// Also note that the background process we are talking about here is actually
		// a "sh" program running the whole "(...)&" statement; this is the process
		// of which we capture the pid.
		$output = /*. (string[int]) .*/ array();
		$ret_var = 0;
//		$this->exec_command = "(cd '" . $this->dir ."' && ((date +%s > started; $command || echo \$? > exit_code; date +%s > finished) & echo \$! > pid) > stdout 2> stderr )&";
		$this->exec_command = "(cd '" . $this->dir ."' && ((date +%s > started; ($command); echo \$? > exit_code; date +%s > finished)& echo \$! > pid) > stdout 2> stderr )&";
			$this->writeProperty("exec_command", $this->exec_command);
		$last_line = exec($this->exec_command, $output, $ret_var);
		if( $ret_var !== 0){
			$this->status = self::STATUS_FINISHED;  $this->writeProperty("status", "".$this->status);
			$this->finished = time(); $this->writeProperty("finished", "".$this->finished);
			$this->exit_code = 1;  $this->writeProperty("exit_code", "".$this->exit_code);
			throw new ErrorException($last_line . "\nOutput: ".implode("\n", $output));
		}
	}
	
	
	/**
	 * 
	 * @return boolean
	 * @throws ErrorException
	 */
	private function isRunning()
	{
		if( $this->pid == 0 )
			return FALSE;
		$cmd = "sh -c " . $this->exec_command;
		$output = /*.(string[int]).*/ array();
		$ret_val = 0;
		$last_line = exec("ps --no-headers --format command -w -w --pid " . $this->pid ." 2>&1", $output, $ret_val);
		if( $ret_val == 0 ){
			return $last_line === $cmd;
		} else if( $ret_val == 1 && $last_line === "" ){
			return FALSE;
		} else {
			throw new ErrorException($last_line);
		}
	}
	
	
	/**
	 * Sends a signal to the offline process. The process may or may not set a signal
	 * handler; if not set, the default action is taken depending on the specific
	 * signal -- see the manual page about signal(7) for details. The exit code of
	 * an abnormally terminated process is 128+n, where n is the signal number.
	 * If the process is still in the "ready" state (that is, it is not running yet)
	 * of the process is already finished, does nothing.
	 * @param string $signal Name of the signal (example: "TERM") or its number as
	 * a string (example: "15"). Normally you should send "TERM" to send a graceful
	 * request for termination a process cannot catch. The "KILL" signal causes an
	 * abrupt termination the process cannot catch. See "man 7 signal" for a complete
	 * list of the allowed signals.
	 * @return int Current status of the offline process BEFORE the signal just sent:
	 * 0 = ready, 1 = running, 2 = finished.
	 * @throws ErrorException Failed to send the signal.
	 */
	function kill($signal)
	{
		switch($this->status){
			case self::STATUS_READY:
				return self::STATUS_READY;
			case self::STATUS_RUNNING:
				$output = /*. (string[int]) .*/ array();
				$ret_val = 0;
				$last_line = exec("pkill -$signal -P " . $this->pid ." 2>&1", $output, $ret_val);
				if( $ret_val == 0 ){
					// Process signaled.
					return self::STATUS_RUNNING;
				} else if( $ret_val == 1 ){
					// No such process.
					return self::STATUS_FINISHED;
				} else {
					// Anything else.
					throw new ErrorException("pkill returned code is $ret_val: $last_line, ".implode("\n", $output));
				}
			case self::STATUS_FINISHED:
				return self::STATUS_FINISHED;
			default:
				throw new RuntimeException();
		}
	}
	
	
	/**
	 * Retrieves the current status of the offline process. Once retrieved, the
	 * internal state of this object does not change anymore; the only way to get
	 * a new, freshen status of the offline process is to call this function again
	 * later.
	 * @param string $ticket
	 * @return self
	 * @throws ErrorException
	 */
	static function retrieve($ticket)
	{
		if(preg_match("/^[0-9a-f]+\$/", $ticket) !== 1 )
			throw new \InvalidArgumentException("ticket=$ticket");
		
		$job = new self();
		$job->ticket = $ticket;
		$job->dir = self::$jobs_directory . "/" . $job->ticket;
		if( ! file_exists($job->dir) )
			throw new ErrorException("this job does not exists");
		$job->name = $job->readProperty("name");
		$job->command = $job->readProperty("command");
		$job->exec_command = $job->readProperty("exec_command");
		$job->pid = (int) $job->readProperty("pid");
		$job->status = (int) $job->readProperty("status");
		
		// Check the actual status of the process:
		switch($job->status){
			case self::STATUS_READY:
				if( $job->isRunning() ){
					$job->status = self::STATUS_RUNNING;  $job->writeProperty("status", "".$job->status);
				} else if( $job->pid > 0 ){
					// Process started and finished before the status of the job be updated.
					$job->status = self::STATUS_FINISHED;  $job->writeProperty("status", "".$job->status);
				}
				break;
			case self::STATUS_RUNNING:
				if( ! $job->isRunning() ){
					$job->status = self::STATUS_FINISHED;  $job->writeProperty("status", "".$job->status);
				}
				break;
			case self::STATUS_FINISHED:
				break;
			default:
				throw new RuntimeException();
		}
		
		$job->stdout = $job->readProperty("stdout");
		$job->stderr = $job->readProperty("stderr");
		$job->created = (int) $job->readProperty("created");
		$job->started = (int) $job->readProperty("started");
		$job->finished = (int) $job->readProperty("finished");
		$job->exit_code = (int) $job->readProperty("exit_code");
		$job->progress_percentage = (int) $job->readProperty("progress_percentage");
		$job->feedback = $job->readProperty("feedback");
		
		return $job;
	}
	
	
	/**
	 * Returns the human-readable name or description of this job.
	 * @return string
	 */
	function getName()
	{
		return $this->name;
	}
	
	
	/**
	 * Returns the issued command.
	 * @return string
	 */
	function getCommand()
	{
		return $this->command;
	}
	
	
	/**
	 * Exit code of the finished process, or zero if not finished yet.
	 * @return int
	 */
	function getExitCode()
	{
		return $this->exit_code;
	}
	
	
	/**
	 * Current content of the standard output of the process.
	 * @return string
	 */
	function getStdout()
	{
		return $this->stdout;
	}
	
	
	/**
	 * Current content of the standard error of the process.
	 * @return string
	 */
	function getStderr()
	{
		return $this->stderr;
	}
	
	
	/**
	 * Returns the ticket that univocally identifies this offline process. This
	 * same value must be used to retrieve the state of the offline process, see
	 * the retrieve() method.
	 * @return string
	 */
	function getTicket()
	{
		return $this->ticket;
	}
	
	
	/**
	 * Returns the processing status as a percentage in the range 0-100.
	 * It is responsibility of the offline process to update this value.
	 * For processes that do not set this value, the returned value is always 0.
	 * Note that the value 100 does not means the process is actually finished to
	 * run, it only means its job is done from the users' point of view.
	 * @return int Value in the range [0,100], where 0 may mean the process is still
	 * initializing itself or it does not provide a percentage of completion at all.
	 */
	function getProgressPercentage()
	{
		$p = $this->progress_percentage;
		if( $p < 0 )
			$p = 0;
		else if( $p > 100 )
			$p = 100;
		return $p;
	}
	
	
	/**
	 * Latest feedback message set by the process.
	 * @return string
	 */
	function getFeedback()
	{
		return $this->feedback;
	}
	
	
	/**
	 * Returns true if the process is finished. Note that the status this function is
	 * referring to is the status of the process at the time this object has been
	 * created, and it is not updated since then.
	 * @return boolean
	 */
	function isFinished()
	{
		return $this->status == self::STATUS_FINISHED;
	}
	
	
	/**
	 * Timestamp of creation of the job.
	 * @return int Unix timestamp (s).
	 */
	function getCreated()
	{
		return $this->created;
	}
	
	
	/**
	 * Timestamp of start of the controlled process.
	 * @return int Unix timestamp (s). Its value is zero if the process is still
	 * in "ready" status.
	 */
	function getStarted()
	{
		return $this->started;
	}
	
	
	/**
	 * Timestamp of finishing of the controlled process.
	 * @return int Unix timestamp (s). Its value is zero if the process is still
	 * not finished.
	 */
	function getFinished()
	{
		return $this->finished;
	}
	
	
	/**
	 * Returns the full path of a property file. Offline processes may generate
	 * arbitrary files under their temporary working directory that are here called
	 * "properties".
	 * @param string $name Name of the property. For portability and security reasons,
	 * only Latin letters, digits, underscore, hyphen and dot are allowed, but the first
	 * character cannot be a dot. Subdirectories are allowed too. Examples:
	 * "record_id", "pages/1.txt"
	 * are allowed too.
	 * @return string
	 * @throws InvalidArgumentException Invalid name.
	 */
	function getPropertyPath($name)
	{
		$w = "[-a-zA-Z0-9_][-a-zA-Z0-9_.]+";
		if(preg_match("/^$w(\\/$w)*\$/", $name) !== 1 )
			throw new InvalidArgumentException("invalid property name: $name");
		return $this->dir . "/$name";
	}
	
	
	/**
	 * Returns the value of a property. Offline processes may generate
	 * arbitrary files under their temporary working directory that are here called
	 * "properties".
	 * @param string $name Name of the property. For portability and security reasons,
	 * only Latin letters, digits, underscore, hyphen and dot are allowed, but the first
	 * character cannot be a dot. Subdirectories are allowed too.
	 * @return string Content of the property, or NULL if the file does not exist.
	 * @throws InvalidArgumentException Invalid name.
	 * @throws ErrorException Failed to read the property file.
	 */
	function getProperty($name)
	{
		$filename = $this->getPropertyPath($name);
		if(file_exists($filename) )
			return file_get_contents($filename);
		else
			return NULL;
	}
	
	
	/**
	 * Recursively deletes a directory and all its contents.
	 * Only files of type "dir", "file" and "socket" are deleted; if any other
	 * type of file "fifo", "char", "block", "link" or "unknown" is found, an
	 * exception is thrown.
	 * @param string $dir
	 * @return void
	 * @throws ErrorException Access failed to the file system. Found an unexpected
	 * type of file.
	 */
	private static function recursivelyDeleteDirectory($dir)
	{
		$d = opendir($dir);
		while( ($fn = readdir($d)) !== FALSE ){
			if( $fn === "." || $fn === ".." )
				continue;
			$full = "$dir/$fn";
			$type = filetype($full);
			if( $type === "dir" )
				self::recursivelyDeleteDirectory($full);
			else if( $type === "file" || $type === "socket" )
				unlink($full);
			else
				throw new ErrorException("unexpected type of file '$type' for $full");
		}
		closedir($d);
		rmdir($dir);
	}
	

	/**
	 * Delete this job from the jobs directory. If the controlled process is still
	 * not finished, an attempt is made to stop it before deleting the working directory..
	 * @throws ErrorException
	 */
	function delete()
	{
		// if ready, wait for the running status:
		if( $this->status == self::STATUS_READY ){
//			echo "FIXME: trying to kill ready job...\n";
			for($i = 3; $i > 0; $i--){
				sleep(1);
				$pid = (int) $this->readProperty("pid");
				if( $pid > 0 ){
					$this->pid = $pid;
					$this->status = self::STATUS_RUNNING;
					break;
				}
			}
		}
		// if running, terminate the process:
		if( $this->status == self::STATUS_RUNNING ){
//			echo "FIXME: trying to kill running job...\n";
			$this->kill("TERM");
			for($i = 3; $i > 0; $i--){
				sleep(1);
				if( ! $this->isRunning() ){
					$this->status = self::STATUS_FINISHED;
					break;
				}
			}
			if( $this->status != self::STATUS_FINISHED ){
				$this->kill("KILL");
				for($i = 3; $i > 0; $i--){
					sleep(1);
					if( ! $this->isRunning() ){
						$this->status = self::STATUS_FINISHED;
						break;
					}
				}
			}
		}
//		if( $this->status !== self::STATUS_FINISHED )
//			echo "FIXME: job still running.\n";
		if( !( file_exists($this->dir."/created") && file_exists($this->dir."/pid") ) )
			throw new ErrorException("the directory does not look like a job's directory as some specific files are missing: ".$this->dir);
		self::recursivelyDeleteDirectory($this->dir);
	}
	
	
	/**
	 * 
	 * @param int $max_age
	 * @throws ErrorException
	 */
	static function deleteFinishedOlderThan($max_age)
	{
		$d = opendir(self::$jobs_directory);
		while( ($fn = readdir($d)) !== FALSE ){
			if( $fn === "." || $fn === ".." )
				continue;
			$now = time();
			try {
				$job = self::retrieve($fn);
//				echo "job ", $job->getTicket(),
//					" created ", ($now - $job->getCreated()), " seconds ago";
				if( $job->isFinished() ){
					$age = $now - $job->getFinished();
//					echo ", finished $age seconds ago";
					if( $age > $max_age ){
//						echo " --> DELETING\n";
						$job->delete();
//					} else {
//						echo "\n";
					}
//				} else {
//					echo ", still running.\n";
				}
			}
			catch(ErrorException $e){
				error_log("$e");
			}
		}
	}
	
}
