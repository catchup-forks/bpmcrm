<?php

namespace App\Models;

use RuntimeException;

/**
 * Description of ScriptDockerTrait
 *
 */
trait ScriptDockerTrait
{

    /**
     * Run a command in a docker container.
     *
     *
     * @return array
     * @throws \RuntimeException
     */
    protected function execute(array $options)
    {
        $container = $this->createContainer($options['image'], $options['command']);
        foreach ($options['inputs'] as $path => $data) {
            $this->putInContainer($container, $path, $data);
        }
        $response = $this->startContainer($container);
        $outputs = [];
        foreach ($options['outputs'] as $name => $path) {
            $outputs[$name] = $this->getFromContainer($container, $path);
        }
        exec(config('app.bpm_scripts_docker') . ' rm ' . $container);
        $response['outputs'] = $outputs;
        return $response;
    }

    /**
     * Create a docker container.
     *
     * @param string $image
     * @param string $command
     * @param string $parameters
     *
     * @return string
     * @throws \RuntimeException
     */
    private function createContainer($image, $command, $parameters = '')
    {
        $cidfile = tempnam(config('app.bpm_scripts_home'), 'cid');
        unlink($cidfile);
        $cmd = config('app.bpm_scripts_docker') . sprintf(' create %s --cidfile %s %s %s &', $parameters, $cidfile, $image, $command);
        exec($cmd, $output, $returnCode);
        if ($returnCode) {
            throw new RuntimeException('Unable to create a docker container: ' . implode("\n", $output));
        }
        $cid = file_get_contents($cidfile);
        unlink($cidfile);
        return $cid;
    }

    /**
     * Put a string content into a file in the container.
     *
     * @param string $container
     * @param string $path
     * @param string $content
     *
     * @throws \RuntimeException
     */
    private function putInContainer($container, $path, $content)
    {
        $source = tempnam(config('app.bpm_scripts_home'), 'put');
        file_put_contents($source, $content);
        $cmd = config('app.bpm_scripts_docker')
            . sprintf(' cp %s %s:%s 2>&1', $source, $container, $path);
        exec($cmd, $output, $returnCode);
        unlink($source);
        if ($returnCode) {
            throw new RuntimeException('Unable to send data to container: ' . implode("\n", $output));
        }
    }

    /**
     * Get the content from a file in the container.
     *
     * @param string $container
     * @param string $path
     *
     * @return string
     * @throws \RuntimeException
     */
    private function getFromContainer($container, $path)
    {
        $target = tempnam(config('app.bpm_scripts_home'), 'get');
        $cmd = config('app.bpm_scripts_docker') . sprintf(' cp %s:%s %s 2>&1', $container, $path, $target);
        exec($cmd, $output, $returnCode);
        $content = file_get_contents($target);
        unlink($target);
        return $content;
    }

    /**
     * Start the container.
     *
     * @param string $container
     *
     * @return array{line: string|false, output: string[], returnCode: int}
     */
    private function startContainer($container): array
    {
        $cmd = config('app.bpm_scripts_docker') . sprintf(' start %s -a 2>&1', $container);
        $line = exec($cmd, $output, $returnCode);
        return compact('line', 'output', 'returnCode');
    }
}
