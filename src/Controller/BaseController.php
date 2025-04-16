<?php

namespace DumpsterfirePages\Controller;

use DumpsterfirePages\Exceptions\ControllerException;
use DumpsterfirePages\Interfaces\ILoggable;
use Throwable;
use TypeError;
use DumpsterfirePages\Interfaces\LoggerInterface;

abstract class BaseController implements ILoggable
{
    protected array $rawParams = [];

    protected ?LoggerInterface $logger = null;

    public function setLogger(LoggerInterface $loggerInterface): self
    {
        $this->logger = $loggerInterface;
        return $this;
    }

    protected function autoAssignParams(array $params): self
    {
        foreach ($params as $key => $value) {
            try {
                if (is_numeric($key)) {
                    continue;
                }

                $this->rawParams[$key] = $value;

                if (property_exists($this, $key)) {
                    $this->$key = $value;
                }
            } catch (TypeError $e) {
                // skip param if types don't match
                $this->logger?->log($e->getMessage());
                continue;
            } catch (Throwable $e) {
                $this->logger?->log($e->getMessage());
                throw new ControllerException($e->getMessage());
            }
        }

        return $this;
    }

    public function getRawParams(): array
    {
        return $this->rawParams;
    }
}