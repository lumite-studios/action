<?php

use LumiteStudios\Action\AbstractAction;
use LumiteStudios\Action\Interfaces\EditInterface;
use LumiteStudios\Action\Interfaces\SaveInterface;
use LumiteStudios\Action\Concerns\HandleErrorsTrait;
use LumiteStudios\Action\Interfaces\CreateInterface;
use LumiteStudios\Action\Interfaces\DeleteInterface;
use LumiteStudios\Action\Concerns\HandleRequestTrait;

test('creates validator in constructor', function () {
	$action = new Class extends AbstractAction {};

	expect($action->getValidator())->toBeInstanceOf(\Illuminate\Validation\Validator::class);
});

test('passes without errors', function () {
	$action = new Class extends AbstractAction {};

	expect(method_exists($action, 'fails'))->toBeTrue();
	expect(method_exists($action, 'passes'))->toBeTrue();
	expect($action->fails())->toBeFalse();
	expect($action->passes())->toBeTrue();
});

test('can handle additional errors with trait', function () {
	$action = new Class extends AbstractAction {
		use HandleErrorsTrait;
		protected function errors(array $attributes): void {}
	};
	$key = 'error_key';
	$value = 'error_value';

	$action->addError($key, $value);

	expect($action->getErrors())->toHaveCount(1);
	expect($action->getErrors()->has($key))->toBeTrue();
	expect($action->getErrors()->get($key)[0])->toBe($value);
});

test('throws validation exception on error', function() {
	$action = new Class extends AbstractAction {
		use HandleErrorsTrait;
		protected function errors(array $attributes): void {}
	};
	$action->addError('error_key', 'error_value');
	$action->handle();
})->throws(\Illuminate\Validation\ValidationException::class);

test('throws authorization exception if authorize fails', function() {
	$action = new Class extends AbstractAction {
		use HandleRequestTrait;
		protected function authorize(): bool { return false; }
		protected function rules(): array { return []; }
	};
	$action->handle();
})->throws(\Illuminate\Auth\Access\AuthorizationException::class);

test('can pass authorization', function () {
	$action = new Class extends AbstractAction {
		use HandleRequestTrait;
		protected function authorize(): bool { return true; }
		protected function rules(): array { return []; }
	};
	$action->handle();

	expect($action->passesAuthorization())->toBeTrue();
});

test('throws validation exception if rules fail', function() {
	$action = new Class extends AbstractAction {
		use HandleRequestTrait;
		protected function authorize(): bool { return true; }
		protected function rules(): array { return ['username' => 'required']; }
	};
	$action->handle();
})->throws(\Illuminate\Validation\ValidationException::class);

test('can alter validation data', function() {
	$action = new Class extends AbstractAction {
		use HandleRequestTrait;
		protected function authorize(): bool { return true; }
		protected function rules(): array { return ['username' => 'required']; }
		protected function prepareForValidation(): array {
			return ['username' => 'test'];
		}
	};
	$action->handle();

	expect($action->passes())->toBeTrue();
});

test('can handle create with interface', function() {
	$action = new Class extends AbstractAction implements CreateInterface {
		public function create(array $attributes) { return 'create'; }
	};
	$state = $action->handle()->run();

	expect($action->passes())->toBeTrue();
	expect($state)->toBe('create');
});

test('can handle edit with interface', function() {
	$action = new Class extends AbstractAction implements EditInterface {
		public function edit(array $attributes) { return 'edit'; }
	};
	$state = $action->handle()->run();

	expect($action->passes())->toBeTrue();
	expect($state)->toBe('edit');
});

test('can handle delete with interface', function() {
	$action = new Class extends AbstractAction implements DeleteInterface {
		public function delete(array $attributes) { return 'delete'; }
	};
	$state = $action->handle()->run();

	expect($action->passes())->toBeTrue();
	expect($state)->toBe('delete');
});

test('can handle save with interface', function() {
	$action = new Class extends AbstractAction implements SaveInterface {
		public function save(array $attributes) { return 'save'; }
	};
	$state = $action->handle()->run();

	expect($action->passes())->toBeTrue();
	expect($state)->toBe('save');
});
