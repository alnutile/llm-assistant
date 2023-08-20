<?php

namespace App\OpenAi;

use Mis3085\Tiktoken\Facades\Tiktoken;

class TokenCalculator
{
    public function __construct(
        public $maxTokens = 1000,
        protected $tokenUsage = 0,
        protected $maxCompletionSize = 0,
    ) {
        $this->maxCompletionSize = $this->maxCompletionSize > 0 ? $this->maxCompletionSize : config('services.openai.max_completion_size');
        Tiktoken::setEncoder('cl100k_base'); // Set the encoder to use
    }

    public function calculate($content): int
    {
        $this->tokenUsage = Tiktoken::count($content);

        return $this->tokenUsage;
    }

    public function calculateStack(array $messageStack)
    {
        $this->tokenUsage = array_reduce($messageStack, function ($carry, $item) {
            if (isset($item['usage'])) {
                return $carry + $item['usage'];
            }

            return $carry + Tiktoken::count($item['content']);
        }, 0);

        return $this->tokenUsage;
    }

    public function isValid(): bool
    {
        return $this->capLeft() >= 0;
    }

    public function capLeft(): int
    {
        $calculatedCapLeft = $this->maxCompletionSize - $this->maxTokens - $this->tokenUsage;

        return max(0, $calculatedCapLeft);
    }

    public function toArray(): array
    {
        return [
            'maxTokens' => $this->maxTokens,
            'tokenUsage' => $this->tokenUsage,
            'isValid' => $this->isValid(),
            'capLeft' => $this->capLeft(),
        ];
    }

    public function shrinkInput(string $content)
    {
        $overage = ($this->maxCompletionSize - Tiktoken::count($content) - $this->maxTokens > 0 ? 0 : $this->maxCompletionSize - Tiktoken::count($content) - $this->maxTokens) * -1;
        if ($overage > 0) {
            $content = Tiktoken::limit($content, mb_strlen($content, 'UTF-8') - $overage);
        }

        return $content;
    }

    public function tokenize($content)
    {
        $tokens = Tiktoken::encode($content);

        return $tokens;
    }
}
