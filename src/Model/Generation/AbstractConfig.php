<?php

declare(strict_types=1);

namespace Mazarini\SymfonAI\Model\Generation;

use JsonSerializable;

abstract class AbstractConfig implements JsonSerializable
{
    protected ?float $temperature = 0.8;
    protected ?float $topP = 0.95;
    protected ?int $maxTokens = 1024;
    protected ?array $stopSequences = null;
    protected ?float $frequencyPenalty = null;
    protected ?float $presencePenalty = null;
    protected ?int $candidateCount = null;

    public function __construct()
    {
    }

    abstract public function toArray(): array;

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public function getTemperature(): ?float
    {
        return $this->temperature;
    }

    public function setTemperature(?float $temperature): self
    {
        $this->temperature = $temperature;

        return $this;
    }

    public function getTopP(): ?float
    {
        return $this->topP;
    }

    public function setTopP(?float $topP): self
    {
        $this->topP = $topP;

        return $this;
    }

    public function getMaxTokens(): ?int
    {
        return $this->maxTokens;
    }

    public function setMaxTokens(?int $maxTokens): self
    {
        $this->maxTokens = $maxTokens;

        return $this;
    }

    public function getStopSequences(): ?array
    {
        return $this->stopSequences;
    }

    public function setStopSequences(?array $stopSequences): self
    {
        $this->stopSequences = $stopSequences;

        return $this;
    }

    public function getFrequencyPenalty(): ?float
    {
        return $this->frequencyPenalty;
    }

    public function setFrequencyPenalty(?float $frequencyPenalty): self
    {
        $this->frequencyPenalty = $frequencyPenalty;

        return $this;
    }

    public function getPresencePenalty(): ?float
    {
        return $this->presencePenalty;
    }

    public function setPresencePenalty(?float $presencePenalty): self
    {
        $this->presencePenalty = $presencePenalty;

        return $this;
    }

    public function getCandidateCount(): ?int
    {
        return $this->candidateCount;
    }

    public function setCandidateCount(?int $candidateCount): self
    {
        $this->candidateCount = $candidateCount;

        return $this;
    }
}