<?php

namespace Larapress\Honeypot;

use Illuminate\Config\Repository as Config;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class Honeypot
{
    /**
     * @var string|array
     */
    private string|array $honeypotFields;
    private Collection $honeypotConfig;
    private string|null $redirectTo = null;

    public function __construct(protected Request $request, protected Config $config, string|array|null $fields = null)
    {
        if(is_string($fields)) {
            $fields = Arr::wrap($fields);
        }
        $this->honeypotConfig = collect($this->config->get('honeypot'));
        $this->honeypotFields = $fields != null ? $fields : $this->honeypotConfig->get('fields');
    }

    /**
     * @return array|mixed|string
     */
    public function fields(): mixed
    {
        return $this->honeypotFields;
    }

    public function isBot(): bool
    {
        return $this->request()->anyFilled($this->honeypotFields);
    }

    public function payload(): array
    {
        return $this->request()->only($this->honeypotFields);
    }

    public function setRedirectTo(string|null $to): void
    {
        $this->redirectTo = $to;
    }

    public function redirectTo(): ?string
    {
        return $this->redirectTo;
    }

    public function updateFields(string|array|null $fields = null): void
    {
        if(! is_array($fields)) {
            $fields = Arr::wrap($fields);
        }
        $this->honeypotFields = $fields;
    }

    public function request(): Request
    {
        return $this->request;
    }

    public function setRequest(Request $request): void
    {
        $this->request = $request;
    }
}
