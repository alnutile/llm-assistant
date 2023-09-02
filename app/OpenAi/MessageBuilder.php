<?php

namespace App\OpenAi;

use App\OpenAi\Dtos\MessageDto;
use App\OpenAi\Dtos\MessagesDto;
use Mis3085\Tiktoken\Facades\Tiktoken;
use SundanceSolutions\LarachainTrimText\Facades\LarachainTrimText;

class MessageBuilder
{
    protected array $messages = [];

    protected string $context_prompt;

    protected string $question_prompt;

    protected TokenCalculator $tokenCalculator;

    protected int $current_count = 0;

    protected int $max_response_size;

    protected string $content_prefix = 'Current Context:';

    public function __construct(int $max_response_size = null, TokenCalculator $tokenCalculator = null)
    {
        if (! $tokenCalculator) {
            $tokenCalculator = new TokenCalculator(maxTokens: $max_response_size);
        }
        $this->tokenCalculator = $tokenCalculator;
        $this->max_response_size = ($max_response_size) ?? config('openai.max_response_size');
    }

    public function getMessages(): array
    {
        return $this->messages;
    }

    public function getMessagesLimitTokenCount($max_token = 5000, $remove_token_count = false): array
    {
        $results = collect($this->messages)
            ->reverse()
            ->takeWhile(
                function ($value, $key) use (&$max_token) {
                    $max_token -= $value['token_count'];

                    return $max_token >= 0;
                })
            ->reverse()
            ->map(function ($item) {
                if (data_get($item, 'function') === null) {
                    unset($item['function']);
                }

                return $item;
            })
            ->values();

        if ($remove_token_count) {
            $results = $results->map(function ($item) {
                unset($item['token_count']);

                return $item;
            });
        }

        return $results->toArray();
    }

    public function getCurrentTokenCount(): int
    {
        return $this->current_count;
    }

    public function getMessagesWithCount(): array
    {
        return collect($this->messages)->map(function ($item) {
            $content = $this->getContentOrFunctionFromItem($item);
            $item['token_count'] = Tiktoken::count($content);

            return $item;
        })->toArray();
    }

    public function setMessages(MessagesDto $messages): self
    {
        $this->messages = collect($messages->messages)->transform(function ($item) {
            $item = $item->toArray();
            if (! data_get($item, 'token_count')) {
                $content = $this->getContentOrFunctionFromItem($item);
                $item['token_count'] = Tiktoken::count($content);
            }
            $this->current_count = $item['token_count'] + $this->current_count;

            return $item;
        })->toArray();

        return $this;
    }

    public function addMessage($context_prompt, $question_prompt): self
    {

        $this->context_prompt = $context_prompt;
        $this->question_prompt = $question_prompt;

        if (empty($this->messages)) {
            $this->setupStartingMessages();
        } else {
            $this->assistantUserMessage();
        }

        return $this;
    }

    public function setContentPrefix(string $prefix = ''): self
    {
        $this->content_prefix = $prefix;

        return $this;
    }

    protected function assistantUserMessage(): void
    {
        $this->addAssistantOrSystemMessageChunked();

        $this->addAMessageAndCount($this->makeMessageArray($this->question_prompt, 'user'));

    }

    protected function addAssistantOrSystemMessageChunked($role = 'assistant'): void
    {
        $content = $this->context_prompt;
        $tokenCount = Tiktoken::count($content);
        $count = 0;
        if ($tokenCount <= $this->max_response_size) {
            $this->addAMessageAndCount(
                $this->makeMessageArray($content, $role)
            );
        } else {
            $start = 0;

            $content = $this->seeIfOverMaxQuestionSize($content);

            while ($start < $tokenCount) {
                $chunk = Tiktoken::limit($content, $this->max_response_size);

                $this->addAMessageAndCount(
                    $this->makeMessageArray($chunk, $role)
                );

                $content = substr($content, strlen($chunk));
                $tokenCount = Tiktoken::count($content);
                $count++;
            }
        }
    }

    protected function addAMessageAndCount(MessageDto $message): void
    {

        $message = $message->toArray();
        $content = $this->getContentOrFunctionFromItem($message);
        $message['token_count'] = Tiktoken::count($content);
        $this->current_count = $message['token_count'] + $this->current_count;
        $this->messages[] = $message;
    }

    protected function setupStartingMessages(): void
    {
        $this->addAssistantOrSystemMessageChunked('system');

        $results = $this->makeMessageArray(
            $this->question_prompt,
            'user'
        );

        $this->addAMessageAndCount($results);
    }

    protected function content_prefix(string $content, string $role = 'user'): string
    {
        /**
         * @TODO talk about this
         */
        if ($role !== 'user') {
            $content = sprintf('%s %s', $this->content_prefix, $content);
        }

        return $content;
    }

    protected function makeMessageArray(
        string $content, string $role = 'system'
    ): MessageDto {

        return MessageDto::from([
            'role' => $role,
            'content' => $content,
        ]);
    }

    protected function seeIfOverMaxQuestionSize(string $content): string
    {
        $contentSize = Tiktoken::count($content);

        if ($contentSize > config('openai.max_question_size')) {
            $content = LarachainTrimText::trim($content);
        }

        return $content;
    }

    protected function getContentOrFunctionFromItem(array $item): string
    {
        if (data_get($item, 'content') !== null) {
            return data_get($item, 'content');
        }

        return data_get($item, 'function', 'no content');
    }
}
