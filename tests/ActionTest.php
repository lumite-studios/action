<?php

use LumiteStudios\Action\Tests\Classes\AllAction;
use LumiteStudios\Action\Tests\Classes\EditAction;
use LumiteStudios\Action\Tests\Classes\RuleAction;
use LumiteStudios\Action\Tests\Classes\SaveAction;
use LumiteStudios\Action\Tests\Classes\TestAction;
use LumiteStudios\Action\Tests\Classes\ErrorAction;
use LumiteStudios\Action\Tests\Classes\CreateAction;
use LumiteStudios\Action\Tests\Classes\DeleteAction;
use LumiteStudios\Action\Tests\Classes\AuthorizeAction;

test('creates a validator in the constructor', function () {
	$action = new TestAction();

	expect($action->getValidator())
		->toBeInstanceOf(\Illuminate\Validation\Validator::class);
});

test('passes without errors when no data is supplied', function () {
	$action = new TestAction();

	expect(method_exists($action, 'fails'))
		->toBeTrue()
		->and(method_exists($action, 'passes'))
		->toBeTrue()
		->and($action->fails())
		->toBeFalse()
		->and($action->passes())
		->toBeTrue();
});

test('can handle additional errors with trait', function () {
	$action = new ErrorAction();
	$key = 'error_key';
	$value = 'error_value';

	$action->addError($key, $value);

	expect($action->fails())
		->toBeTrue()
		->and($action->passes())
		->toBeFalse()
		->and($action->getErrors())
		->toHaveCount(1)
		->and($action->getErrors()->has($key))
		->toBeTrue()
		->and($action->getErrors()->get($key)[0])
		->toBe($value);
});

test('throws validation exception on error', function () {
	$action = new ErrorAction();
	$action->addError('error_key', 'error_value');
	$action->handle();
})->throws(\Illuminate\Validation\ValidationException::class);

test('throws authorization exception if authorize fails', function () {
	$action = new AuthorizeAction();
	$action->handle();
})->throws(\Illuminate\Auth\Access\AuthorizationException::class);

test('can pass authorization', function () {
	$action = new AllAction();
	$action->handle();

	expect($action->passesAuthorization())
		->toBeTrue();
});

test('throws validation exception if rules fail', function () {
	$action = new RuleAction();
	$action->handle();
})->throws(\Illuminate\Validation\ValidationException::class);

test('can alter validation data', function () {
	$action = new RuleAction();
	$action->handle(['username' => 'test']);

	expect($action->passes())->toBeTrue();
});

test('can handle create with interface', function () {
	$action = new CreateAction();
	$state = $action->handle()->run();

	expect($action->passes())
		->toBeTrue()
		->and($state)
		->toBe('create');
});

test('can handle edit with interface', function () {
	$action = new EditAction();
	$state = $action->handle()->run();

	expect($action->passes())
		->toBeTrue()
		->and($state)
		->toBe('edit');
});

test('can handle delete with interface', function () {
	$action = new DeleteAction();
	$state = $action->handle()->run();

	expect($action->passes())
		->toBeTrue()
		->and($state)
		->toBe('delete');
});

test('can handle save with interface', function () {
	$action = new SaveAction();
	$state = $action->handle()->run();

	expect($action->passes())
		->toBeTrue()
		->and($state)
		->toBe('save');
});
