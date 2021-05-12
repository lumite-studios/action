<?php
namespace LumiteStudios\Action\Tests;

use LumiteStudios\Action\Action;
use Illuminate\Http\RedirectResponse;
use LumiteStudios\Action\Tests\TestCase;
use Illuminate\Auth\Access\AuthorizationException;
use LumiteStudios\Action\Interfaces\IEditInterface;
use Illuminate\Http\Exceptions\HttpResponseException;
use LumiteStudios\Action\Interfaces\ICreateInterface;
use LumiteStudios\Action\Interfaces\IDeleteInterface;
use LumiteStudios\Action\Interfaces\IRequestInterface;

class ActionTest extends TestCase
{
	/** @test */
	public function it_does_have_empty_message_bag_before_resolution()
	{
		$action = new Class extends Action {
			protected function errors(array $attributes): void {}
		};

		$this->assertTrue($action->getErrors()->isEmpty(), 'The errors on an Action should be empty at start.');
	}

	/** @test */
	public function it_can_add_error()
	{
		$action = new Class extends Action {
			protected function errors(array $attributes): void {}
		};

		$key = 'error_key';
		$value = 'error_value';

		$action->addError($key, $value);

		$this->assertCount(1, $action->getErrors(), 'Failed to add an error to an Action.');
		$this->assertTrue($action->getErrors()->has($key), 'Could not fetch an error by its key.');
		$this->assertEquals($action->getErrors()->get($key)[0], $value, 'The error value did not match the added value.');
	}

	/** @test */
	public function it_creates_redirect_if_error_added()
	{
		$action = new Class extends Action {
			protected function errors(array $attributes): void {
				$this->addError('test', 'test');
			}
		};

		$this->assertInstanceOf(RedirectResponse::class, $action->failedValidation(), 'A failed validation check should redirect.');
	}

	/** @test */
	public function it_throws_exception_if_action_has_authorization_that_fails()
	{
		$this->assertThrows(AuthorizationException::class, function() {
			$action = new Class extends Action implements IRequestInterface {
				protected function errors(array $attributes): void {}
				public function authorize(): bool { return false; }
				public function rules(): array { return []; }
			};
		});
	}

	/** @test */
	public function it_can_pass_authorization()
	{
		$action = new Class extends Action implements IRequestInterface {
			protected function errors(array $attributes): void {}
			public function authorize(): bool { return true; }
			public function rules(): array { return []; }
		};

		$this->assertTrue($action->passesAuthorization(), 'Failed to pass authorization.');
	}

	/** @test */
	public function it_can_fail_validation()
	{
		$action = new Class extends Action implements IRequestInterface {
			protected function errors(array $attributes): void {}
			public function authorize(): bool { return true; }
			public function rules(): array { return ['username' => 'required']; }
		};

		$this->assertFalse($action->passes(), 'An Action that does not pass the validation rules, should cause the passes() method to be false.');
	}

	/** @test */
	public function it_creates_redirect_if_error_added_via_validation_rules()
	{
		$action = new Class extends Action implements IRequestInterface {
			protected function errors(array $attributes): void {}
			public function authorize(): bool { return true; }
			public function rules(): array { return ['username' => 'required']; }
		};

		$this->assertInstanceOf(RedirectResponse::class, $action->failedValidation());
	}

	/** @test */
	public function it_can_alter_validation_data()
	{
		$action = new Class extends Action implements IRequestInterface {
			protected function errors(array $attributes): void {}
			public function authorize(): bool { return true; }
			public function rules(): array { return ['username' => 'required']; }
		};

		$this->assertFalse($action->passes());
		$action->alterData(['username' => 'test']);
		$this->assertTrue($action->passes());
	}

	/** @test */
	public function it_can_handle_create_action()
	{
		$action = new Class extends Action implements ICreateInterface {
			protected function errors(array $attributes): void {}
			public function create(array $attributes) { return 'create'; }
		};

		$state = $action->handle();

        $this->assertEquals('create', $state);
	}

	/** @test */
	public function it_can_handle_edit_action()
	{
		$action = new Class extends Action implements IEditInterface {
			protected function errors(array $attributes): void {}
			public function edit(array $attributes) { return 'edit'; }
		};

		$state = $action->handle();

        $this->assertEquals('edit', $state);
	}

	/** @test */
	public function it_can_handle_delete_action()
	{
		$action = new Class extends Action implements IDeleteInterface {
			protected function errors(array $attributes): void {}
			public function delete(array $attributes) { return 'delete'; }
		};

		$state = $action->handle();

        $this->assertEquals('delete', $state);
	}
}
