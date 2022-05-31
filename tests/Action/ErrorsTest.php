<?php

use LumiteStudios\Action\Action;

class AsControllerFailErrors extends Action
{
    public function handle()
    {
        $this->resolveErrors([]);
    }

    public function errors(): void
    {
        $this->addError('field', 'message');
    }
}

test('an action can fail with custom errors', function () {
    Route::get('/', AsControllerFailErrors::class);

    expect(fn () => $this->get("/"))
        ->toBeInvalid([
            'field' => 'message',
        ]);
});
