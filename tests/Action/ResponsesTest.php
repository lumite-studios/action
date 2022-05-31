<?php

use Illuminate\Http\Request;
use LumiteStudios\Action\Action;
use Illuminate\Foundation\Auth\User;

class AsControllerResponses extends Action
{
    public function handle()
    {
        return 'handle';
    }

    public function response()
    {
        return 'response';
    }

    public function jsonResponse()
    {
        return 'jsonResponse';
    }
}

test('an action can be invoked', function () {
    $action = new AsControllerResponses;

    expect($action())
        ->toBe('handle');
});

test('an action can be an invokable controller', function () {
    Route::get('/', AsControllerResponses::class);

    $response = $this->get("/")->baseResponse;

    expect($response->getOriginalContent())
        ->toBe('response');
});

test('an action can return json', function () {
    Route::get('/', AsControllerResponses::class);

    $response = $this->json("GET", "/");

    expect($response->getOriginalContent())
        ->toBe('jsonResponse');
});

class AsControllerParameters extends Action
{
    public function handle(Request $request)
    {
        return $request->all();
    }
}

test('an action can pass route bindings', function () {
    Route::post('/', AsControllerParameters::class)->middleware(\Illuminate\Routing\Middleware\SubstituteBindings::class);
    $attributes = [
        'field' => 'message',
    ];

    $response = $this->post("/", $attributes);

    expect($response->baseResponse->getOriginalContent())
        ->toMatchArray($attributes);
});
