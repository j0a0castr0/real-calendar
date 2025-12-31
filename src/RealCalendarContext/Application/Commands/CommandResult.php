<?php

namespace App\RealCalendarContext\Application\Commands;

enum CommandResultType: string
{
    case SUCCESS = 'success';
    case DOMAIN_ERROR = 'domain_error';
    case NOT_FOUND = 'not_fount';
    case FAILURE = 'failure';
}

final class CommandResult
{
    public function __construct(
        private CommandResultType $type,
        private ?string                                     $message = null,
        private ?array                                      $data = null
    ) {}

    public function type(): CommandResultType
    {
        return $this->type;
    }

    public function message(): ?string
    {
        return $this->message;
    }

    public function data(): ?array
    {
        return $this->data;
    }

    public static function success(array $data = [], string $message = null): self
    {
        return new self(CommandResultType::SUCCESS, $message, $data);
    }

    public static function domainError(string $message, array $data = []): self
    {
        return new self(CommandResultType::DOMAIN_ERROR, $message, $data);
    }

    public static function failure(string $message, array $data = []): self
    {
        return new self(CommandResultType::FAILURE, $message, $data);
    }

    public static function notFound(string $message, array $data = []): self
    {
        return new self(CommandResultType::NOT_FOUND, $message, $data);
    }
}
