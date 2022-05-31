<?php

use LumiteStudios\Action\Action;

class AsControllerValidation extends Action
{
    public function rules(): array
    {
        return [
            'attribute' => ['required'],
        ];
    }

    public function handle()
    {
        return 'handle';
    }
}

test('an action can fail validation', function () {
    Route::get('/', AsControllerValidation::class);

    expect(fn () => $this->get("/"))
        ->toBeInvalid([
            'attribute' => __('validation.required', ['attribute' => 'attribute']),
        ]);
});

test('an action can pass validation', function () {
    Route::get('/', AsControllerValidation::class);

    $response = $this->get("/?attribute=test");
    $response->assertOk();

    Route::post('/post', AsControllerValidation::class)->middleware([\Illuminate\Routing\Middleware\SubstituteBindings::class]);

    $response = $this->post("/post", [
        'attribute' => 'test',
    ]);
    $response->assertOk();
});
