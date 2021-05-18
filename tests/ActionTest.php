<?php
namespace LumiteStudios\Action\Tests;

use LumiteStudios\Action\Action;
use Illuminate\Validation\Validator;
use LumiteStudios\Action\Concerns\HandleErrors;
use LumiteStudios\Action\Concerns\HandleRequest;
use LumiteStudios\Action\Interfaces\IEditInterface;
use LumiteStudios\Action\Interfaces\ISaveInterface;
use LumiteStudios\Action\Interfaces\ICreateInterface;
use LumiteStudios\Action\Interfaces\IDeleteInterface;

class ActionTest extends TestCase
{
	/** @test */
	public function it_does_create_validator_in_constructor()
	{
		$action = new Class extends Action {};

		$this->assertInstanceOf(Validator::class, $action->getValidator(), 'Action must instantiate a validator in the constructor.');
	}

	/** @test */
	public function it_passes_without_errors()
	{
		$action = new Class extends Action {};

		$this->assertTrue(method_exists($action, 'fails'), 'An Action must have a "fails" method.');
		$this->assertTrue(method_exists($action, 'passes'), 'An Action must have a "passes" method.');
		$this->assertFalse($action->fails());
		$this->assertTrue($action->passes());
	}

	/** @test */
	public function it_can_add_error_with_handle_errors_trait()
	{
		$action = new Class extends Action {
			use HandleErrors;
			protected function errors(array $attributes): void {}
		};
		$key = 'error_key';
		$value = 'error_value';

		$action->addError($key, $value);

		$this->assertCount(1, $action->getErrors(), 'The Action errors should contain an added error.');
		$this->assertTrue($action->getErrors()->has($key), 'The Action errors should contain the added error key.');
		$this->assertEquals($value, $action->getErrors()->get($key)[0], 'The Action errors should contain the added error value.');
	}

	/** @test */
	public function it_throws_validation_exception_if_error_added()
	{
		$this->assertThrows(\Illuminate\Validation\ValidationException::class, function() {
			$action = new Class extends Action {
				use HandleErrors;
				protected function errors(array $attributes): void {
					$this->addError('test', 'test');
				}
			};
		});
	}

	/** @test */
	public function it_throws_authorization_exception_if_authorize_fails()
	{
		$this->assertThrows(\Illuminate\Auth\Access\AuthorizationException::class, function() {
			$action = new Class extends Action {
				use HandleRequest;
				protected function authorize(): bool { return false; }
				protected function rules(): array { return []; }
			};
		});
	}

	/** @test */
	public function it_can_pass_authorization()
	{
		$action = new Class extends Action {
			use HandleRequest;
			protected function authorize(): bool { return true; }
			protected function rules(): array { return []; }
		};

		$this->assertTrue($action->passesAuthorization(), 'An Action must be able to pass authorization.');
	}

	/** @test */
	public function it_throws_validation_exception_if_rules_failed()
	{
		$this->assertThrows(\Illuminate\Validation\ValidationException::class, function() {
			$action = new Class extends Action {
				use HandleRequest;
				protected function authorize(): bool { return true; }
				protected function rules(): array { return ['username' => 'required']; }
			};
		});
	}

	/** @test */
	public function it_can_alter_validation_data()
	{
		$action = new Class extends Action {
			use HandleRequest;
			protected function authorize(): bool { return true; }
			protected function rules(): array { return ['username' => 'required']; }
			protected function prepareForValidation(): array {
				return ['username' => 'test'];
			}
		};

		$this->assertTrue($action->passes(), 'Altering the validation data should let the validator pass.');
	}

	/** @test */
	public function it_can_handle_create_action()
	{
		$action = new Class extends Action implements ICreateInterface {
			public function create(array $attributes) { return 'create'; }
		};

		$state = $action->handle();

        $this->assertEquals('create', $state);
	}

	/** @test */
	public function it_can_handle_edit_action()
	{
		$action = new Class extends Action implements IEditInterface {
			public function edit(array $attributes) { return 'edit'; }
		};

		$state = $action->handle();

        $this->assertEquals('edit', $state);
	}

	/** @test */
	public function it_can_handle_delete_action()
	{
		$action = new Class extends Action implements IDeleteInterface {
			public function delete(array $attributes) { return 'delete'; }
		};

		$state = $action->handle();

        $this->assertEquals('delete', $state);
	}

	/** @test */
	public function it_can_handle_save_action()
	{
		$action = new Class extends Action implements ISaveInterface {
			public function save(array $attributes) { return 'save'; }
		};

		$state = $action->handle();

        $this->assertEquals('save', $state);
	}
}
