<?php

use LumiteStudios\Action\Action;

test('must have a handle function', function () {
    expect(fn () => new class extends Action
    {
    })->toThrow(\LumiteStudios\Action\Exceptions\ActionException::class);
});

class ActionWithAsControllerMethod extends Action
{
    public function handle()
    {
        return 'handle';
    }

    public function asController()
    {
        return 'asController';
    }
}

test('an action can prioritize the "asController" method', function () {
    Route::get('/', ActionWithAsControllerMethod::class);

    $response = $this->get("/")->baseResponse;

    expect($response->getOriginalContent())
        ->toBe('asController');
});
