<?php

namespace Tests\Feature;

use App\OpenAi\Dtos\MessagesDto;
use App\OpenAi\MessageBuilder;
use Tests\TestCase;

class MessageBuilderTest extends TestCase
{
    public function test_builds_first_level()
    {
        $context_prompt = 'This is a podcast transcript about X, Y, and Z.';
        $question_prompt = 'Can you summarize this podcast transcript?';

        $messageBuilder = new MessageBuilder();
        $messageBuilder->addMessage($context_prompt, $question_prompt);

        $this->assertEquals(
            [
                ['role' => 'system', 'content' => 'This is a podcast transcript about X, Y, and Z.', 'token_count' => 13],
                ['role' => 'user', 'content' => 'Can you summarize this podcast transcript?', 'token_count' => 7],
            ],
            $messageBuilder->getMessages()
        );
    }

    public function test_builds_second_level()
    {
        $context_prompt = 'This is a podcast transcript about X, Y, and Z.';
        $question_prompt = 'Can you summarize this podcast transcript?';
        $messageBuilder = new MessageBuilder();
        $messageBuilder->addMessage($context_prompt, $question_prompt);
        $context_prompt = '[insert summary from Play 1]';
        $question_prompt = 'Can you pull out some key talking points from this summary?';
        $messageBuilder->addMessage($context_prompt, $question_prompt);
        $this->assertEquals(
            [
                [
                    'role' => 'system',
                    'content' => 'This is a podcast transcript about X, Y, and Z.',
                    'token_count' => 13,
                ],
                [
                    'role' => 'user',
                    'content' => 'Can you summarize this podcast transcript?',
                    'token_count' => 7,
                ],
                [
                    'role' => 'assistant',
                    'content' => '[insert summary from Play 1]',
                    'token_count' => 8,
                ],
                [
                    'role' => 'user',
                    'content' => 'Can you pull out some key talking points from this summary?',
                    'token_count' => 12,
                ],
            ],
            $messageBuilder->getMessages()
        );
        $this->assertEquals(40, $messageBuilder->getCurrentTokenCount());
    }

    public function test_with_count()
    {
        $context_prompt = 'This is a podcast transcript about X, Y, and Z.';
        $question_prompt = 'Can you summarize this podcast transcript?';
        $messageBuilder = new MessageBuilder();
        $messageBuilder->addMessage($context_prompt, $question_prompt);
        $this->assertEquals(
            [
                [
                    'role' => 'system',
                    'content' => 'This is a podcast transcript about X, Y, and Z.',
                    'token_count' => 13,
                ],
                [
                    'role' => 'user',
                    'content' => 'Can you summarize this podcast transcript?',
                    'token_count' => 7,
                ],
            ],
            $messageBuilder->getMessagesWithCount()
        );

        $this->assertEquals(20, $messageBuilder->getCurrentTokenCount());
    }

    public function test_expands_assistant()
    {
        $context_prompt = 'This is a podcast transcript about X, Y, and Z.';
        $question_prompt = 'Can you summarize this podcast transcript?';
        $messageBuilder = new MessageBuilder();
        $messageBuilder->addMessage($context_prompt, $question_prompt);
        $large_content = get_fixture_v2('large_content.txt', false);
        $context_prompt = $large_content;
        $question_prompt = 'Can you pull out some key talking points from this summary?';
        $messageBuilder->addMessage($context_prompt, $question_prompt);
        $this->assertCount(4, $messageBuilder->getMessages());
        $this->assertCount(1, collect($messageBuilder->getMessages())->filter(
            fn ($item) => $item['role'] === 'assistant'
        ));
    }

    public function test_builds_third_level()
    {
        $context_prompt = 'This is a podcast transcript about X, Y, and Z.';
        $question_prompt = 'Can you summarize this podcast transcript?';
        $messageBuilder = new MessageBuilder();
        $messageBuilder->addMessage($context_prompt, $question_prompt);
        $context_prompt = '[insert summary from Play 1]';
        $question_prompt = 'Can you pull out some key talking points from this summary?';
        $messageBuilder->addMessage($context_prompt, $question_prompt);
        $context_prompt = '[insert key talking points from Play 2]';
        $question_prompt = 'Can you convert these talking points into a paragraph suitable for a LinkedIn post?';
        $messageBuilder->addMessage($context_prompt, $question_prompt);

        $this->assertEquals(
            [
                [
                    'role' => 'system',
                    'content' => 'This is a podcast transcript about X, Y, and Z.',
                    'token_count' => 13,
                ],
                [
                    'role' => 'user',
                    'content' => 'Can you summarize this podcast transcript?',
                    'token_count' => 7,
                ],
                [
                    'role' => 'assistant',
                    'content' => '[insert summary from Play 1]',
                    'token_count' => 8,
                ],
                [
                    'role' => 'user',
                    'content' => 'Can you pull out some key talking points from this summary?',
                    'token_count' => 12,
                ],
                [
                    'role' => 'assistant',
                    'content' => '[insert key talking points from Play 2]',
                    'token_count' => 10,
                ],
                [
                    'role' => 'user',
                    'content' => 'Can you convert these talking points into a paragraph suitable for a LinkedIn post?',
                    'token_count' => 15,
                ],
            ],
            $messageBuilder->getMessages()
        );

        $this->assertEquals(65, $messageBuilder->getCurrentTokenCount());
    }

    public function test_limit_message_results()
    {
        $context_prompt = 'This is a podcast transcript about X, Y, and Z.';
        $question_prompt = 'Can you summarize this podcast transcript?';
        $messageBuilder = new MessageBuilder();
        $messageBuilder->addMessage($context_prompt, $question_prompt);
        $context_prompt = '[insert summary from Play 1]';
        $question_prompt = 'Can you pull out some key talking points from this summary?';
        $messageBuilder->addMessage($context_prompt, $question_prompt);
        $context_prompt = '[insert key talking points from Play 2]';
        $question_prompt = 'Can you convert these talking points into a paragraph suitable for a LinkedIn post?';
        $messageBuilder->addMessage($context_prompt, $question_prompt);

        $this->assertEquals(
            [
                [
                    'role' => 'user',
                    'content' => 'Can you pull out some key talking points from this summary?',
                    'token_count' => 12,
                ],
                [
                    'role' => 'assistant',
                    'content' => '[insert key talking points from Play 2]',
                    'token_count' => 10,
                ],
                [
                    'role' => 'user',
                    'content' => 'Can you convert these talking points into a paragraph suitable for a LinkedIn post?',
                    'token_count' => 15,
                ],
            ],
            $messageBuilder->getMessagesLimitTokenCount(40)
        );

    }

    public function test_unsets_token_count()
    {
        $context_prompt = 'This is a podcast transcript about X, Y, and Z.';
        $question_prompt = 'Can you summarize this podcast transcript?';
        $messageBuilder = new MessageBuilder();
        $messageBuilder->addMessage($context_prompt, $question_prompt);
        $context_prompt = '[insert summary from Play 1]';
        $question_prompt = 'Can you pull out some key talking points from this summary?';
        $messageBuilder->addMessage($context_prompt, $question_prompt);
        $context_prompt = '[insert key talking points from Play 2]';
        $question_prompt = 'Can you convert these talking points into a paragraph suitable for a LinkedIn post?';
        $messageBuilder->addMessage($context_prompt, $question_prompt);

        $this->assertEquals(
            [
                [
                    'role' => 'user',
                    'content' => 'Can you pull out some key talking points from this summary?',
                ],
                [
                    'role' => 'assistant',
                    'content' => '[insert key talking points from Play 2]',
                ],
                [
                    'role' => 'user',
                    'content' => 'Can you convert these talking points into a paragraph suitable for a LinkedIn post?',
                ],
            ],
            $messageBuilder->getMessagesLimitTokenCount(40, true)
        );

    }

    public function test_setter()
    {
        $messagesOriginal = [
            ['role' => 'system', 'content' => 'This is a podcast transcript about X, Y, and Z.', 'token_count' => 13],
            ['role' => 'user', 'content' => 'Can you summarize this podcast transcript?', 'token_count' => 7],
            ['role' => 'assistant', 'content' => '[insert summary from Play 1]', 'token_count' => 8],
            ['role' => 'user', 'content' => 'Can you pull out some key talking points from this summary?', 'token_count' => 12],
        ];

        $messages = MessagesDto::createFromArray($messagesOriginal);
        $messageBuilder = new MessageBuilder();
        $messageBuilder->setMessages($messages);

        $this->assertEquals(
            $messagesOriginal,
            $messageBuilder->getMessages());
    }

    public function test_get_count()
    {
        $messagesOriginal = [
            ['role' => 'system', 'content' => 'This is a podcast transcript about X, Y, and Z.', 'token_count' => 13],
            ['role' => 'user', 'content' => 'Can you summarize this podcast transcript?', 'token_count' => 7],
            ['role' => 'assistant', 'content' => '[insert summary from Play 1]', 'token_count' => 8],
            ['role' => 'user', 'content' => 'Can you pull out some key talking points from this summary?', 'token_count' => 12],
            ['role' => 'user', 'content' => null, 'token_count' => 2, 'function' => 'foobar'],
        ];

        $messages = MessagesDto::createFromArray($messagesOriginal);
        $messageBuilder = new MessageBuilder();
        $messageBuilder->setMessages($messages);

        $this->assertEquals(41, $messageBuilder->getCurrentTokenCount());
    }
}
