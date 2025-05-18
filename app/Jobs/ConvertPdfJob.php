<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

class ConvertPdfJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public string $uuid, public string $originalName) {}

    public function handle(): void
    {
        $inputDir = storage_path("app/convert2md/input");
        $outputDir = storage_path("app/convert2md/output");

        $command = $this->buildDockerCommand($inputDir, $outputDir);
        $this->executeConversion($command);
    }

    private function buildDockerCommand(string $inputDir, string $outputDir): string
    {
        $dockerCmd = [
            'docker',
            'run',
            '--rm',
            '-v',
            "$inputDir:/app/input",
            '-v',
            "$outputDir:/app/output",
            'marker-converter',
            "/app/input/{$this->uuid}.pdf",
            '--output_format',
            'markdown',
            '--output_dir',
            '/app/output'
        ];

        return implode(' ', array_map('escapeshellarg', $dockerCmd));
    }

    private function executeConversion(string $command): void
    {
        try {
            $process = Process::fromShellCommandline($command, null, ['PATH' => getenv('PATH')]);
            $process->setTimeout(900);

            $uuid = $this->uuid;
            Cache::put("convert:$uuid:log", "", now()->addMinutes(30));

            $process->run(function ($type, $buffer) use ($uuid) {
                $currentLog = Cache::get("convert:$uuid:log", '');
                $newLog = $currentLog . $buffer;
                Cache::put("convert:$uuid:log", $newLog, now()->addMinutes(30));

                Log::info("Convert [$uuid] - {$type}: " . trim($buffer));
            });

            $this->handleProcessResult($process);
        } catch (\Throwable $e) {
            $this->handleException($e);
        }
    }

    private function handleProcessResult(Process $process): void
    {
        $uuid = $this->uuid;
        if ($process->isSuccessful()) {
            Cache::put("convert:$uuid:status", 'done', now()->addMinutes(30));
        } else {
            $errorOutput = $process->getErrorOutput();
            Cache::put("convert:$uuid:status", 'failed', now()->addMinutes(30));
            Cache::put("convert:$uuid:error", $errorOutput, now()->addMinutes(30));
            Log::error("Convert Job Failed [$uuid]: $errorOutput");
        }
    }

    private function handleException(\Throwable $e): void
    {
        $uuid = $this->uuid;
        Cache::put("convert:$uuid:status", 'failed', now()->addMinutes(30));
        Cache::put("convert:$uuid:error", $e->getMessage(), now()->addMinutes(30));
        Log::error("Docker Execution Exception [$uuid]: " . $e->getMessage());
    }
}
