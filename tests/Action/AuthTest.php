<?php

use LumiteStudios\Action\Action;

class AsControllerFailAuth extends Action
{
    public function authorize(): bool
    {
        return false;
    }

    public function handle()
    {
        return 'handle';
    }
}

test('an action can fail auth', function () {
    Route::get('/', AsControllerFailAuth::class);

    expect(fn () => $this->get("/"))
        ->toThrowAuth();
});

class AsControllerPassAuth extends Action
{
    public function authorize(): bool
    {
        return true;
    }

    public function handle()
    {
        return 'handle';
    }
}

test('an action can pass auth', function () {
    Route::get('/', AsControllerPassAuth::class);

    expect(fn () => $this->get("/"))
        ->not->toThrowAuth()
        ->and($this->get("/")->baseResponse->getOriginalContent())
        ->toBe('handle');
});
