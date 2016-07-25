<?php

require_once __DIR__."/../../../../../stdlib/all.php";

use it\icosaedro\web\OfflineJob;

/**
 * Continuosly displays the state of a job up to its ending.
 * @param string $ticket
 * @throws ErrorException
 */
function monitorJob($ticket)
{
	do {
		$job = OfflineJob::retrieve($ticket);
//		echo "$job\n";
		$percent = $job->getProgressPercentage();
		if( $percent > 0 )
			echo "$percent%";
		$feedback = trim($job->getFeedback());
		if( strlen($feedback) > 0 )
			echo " $feedback";
		if( ! $job->isFinished() && $percent == 0 && strlen($feedback) == 0 )
			echo "RUNNING...";
		echo "\n";
		if( $job->isFinished() ){
			echo "EXIT CODE: ", $job->getExitCode(), "\n";
			echo "STDOUT: ", $job->getStdout(), "\n";
			echo "STDERR: ", $job->getStderr(), "\n";
			$started = $job->getStarted();
			$finished = $job->getFinished();
			if( $started > 0 && $finished > 0 ){
				$dt = $finished - $started;
				echo "TOTAL EXECUTION TIME: $dt s\n";
			}
			break;
		}
		sleep(1);
	} while(TRUE);
}


/**
 * Create job and monitor up to its end.
 * @param string $command
 * @throws ErrorException
 */
function doJob($command)
{
	echo "Test: creating job: $command\n";
	$job = OfflineJob::create("Test job");
	$job->start($command);
	$ticket = $job->getTicket();
	monitorJob($ticket);
}


/**
 * Test: delete a job in ready state.
 * @throws ErrorException
 */
function killReadyJob()
{
	echo "Test: kill a ready job:\n";
	$job = OfflineJob::create("Kill ready job test");
	$job->start("sleep 10");
	// this should kill the job still in "ready" status.
	$job->delete();
}


/**
 * Test: delete a running job.
 * @throws ErrorException
 */
function killRunningJob()
{
	echo "Test: kill a running job:\n";
	$job = OfflineJob::create("Kill running job test");
	$job->start("sleep 5");
	$ticket = $job->getTicket();
	sleep(1);
	$job = OfflineJob::retrieve($ticket);
	monitorJob($ticket);
	$job->delete();
}

/**
 * Creates a job with a process that gives feedback and captures SIGTERM.
 * @return string $ticket
 * @throws ErrorException
 */
function processCapturingSignal()
{
	$src = <<<EOT
#include <stdio.h>
#include <signal.h>
#include <unistd.h>
#include <stdlib.h>
#include <string.h>

int received_signal = 0;

void sig_handler(int signo)
{
	received_signal = signo;
	printf("Process: received signal %d\\n", signo);
}
			
void writeParameter(char * name, char * value)
{
	FILE * f;
	f = fopen(name, "w");
	fwrite(value, strlen(value), 1, f);
	fclose(f);
}

int main(void)
{
	int i;
	char s[100];
	
	if (signal(SIGTERM, sig_handler) == SIG_ERR){
		fprintf(stderr, "can't catch signal %d\\n", SIGTERM);
		exit(1);
	}
	sleep(2);
	for(i = 1; i <= 100; i++){
		sleep(1);
			
		sprintf(s, "Still running with i=%d", i);
		writeParameter("feedback", s);
			
		sprintf(s, "%d", i);
		writeParameter("progress_percentage", s);
			
		printf("Still running with i=%d\\n", i);
		
		if( received_signal != 0 ){
			
			sprintf(s, "Terminated by signal %d", received_signal);
			writeParameter("feedback", s);
			
			printf("Terminated by signal %d\\n", received_signal);
			fprintf(stderr, "Terminated by signal %d\\n", received_signal);
			
			return 123;
		}
	}
	writeParameter("feedback", "My work is terminated.");
	return 0;
}
EOT;
	// Create a job temporary directory...
	$job = OfflineJob::create("Testing with a C program capturing signals");
	// ...and write a C test program in it:
	$src_file = $job->getPropertyPath("test.c");
	file_put_contents($src_file, $src);
	// Compile and execute the test program:
	$job_dir = dirname($src_file);
	$exit_code = 0;
	system("cd $job_dir && cc -Wall -o test.exe test.c", $exit_code);
	if( $exit_code != 0 )
		throw new ErrorException("compilation of the test program failed");
	$job->start("$job_dir/test.exe");
	return $job->getTicket();
}


/**
 * @throws ErrorException
 */
function main()
{
	OfflineJob::$jobs_directory = __DIR__."/jobs";
	if( !file_exists(OfflineJob::$jobs_directory) )
		mkdir(OfflineJob::$jobs_directory);
	OfflineJob::deleteFinishedOlderThan(3600);
//	doJob("for p in 10 20 30 40 50 60 70 80 90 100; do sleep 2; echo \$p > progress_percentage; echo \"Processing record no. \$p...\" > feedback; done; echo 'All done, bye!'");
//	doJob("sleep 500");
	killReadyJob();
	killRunningJob();
	
	$ticket = processCapturingSignal();
	sleep(2);
	$job = OfflineJob::retrieve($ticket);
	$job->kill("TERM");
//	monitorJob($ticket);
	$job = OfflineJob::retrieve($ticket);
	$exit_code = $job->getExitCode();
	if( $exit_code != 123 )
		echo "Test failed: expected exit code 1 but got $exit_code\n";
}
main();
