# lumite-studios/action

![PHP ^8.0](https://img.shields.io/badge/PHP-%5E8.0-787CB5?style=for-the-badge&logo=php)
[![codecov](https://img.shields.io/codecov/c/github/lumite-studios/action/main?label=codecov&style=for-the-badge&token=JLOQF31K23)](https://codecov.io/gh/lumite-studios/action)

## Documentation

### Install
```bash
composer require lumite-studios/action
```

### Testing
``` bash
composer test
```

### Usage
The `\LumiteStudios\Action\AbstractAction` class can be used to simplify creating, editing, and deleting various resources.

#### Controller
```php
public function UserController extends Controller
{
    public function store(CreateUserAction $action)
    {
        $state = $action->handle()->run(); // OR $action->handle()->create($action->getValidated());
        $user = User::where('id', '=', $state)->first();
        return response()->json('User created.', 201);
    }

    public function update(int $user_id, EditUserAction $action)
    {
        $state = $action->handle()->run(); // OR $action->handle()->edit($action->getValidated());
        $user = User::where('id', '=', $state)->first();
        return response()->json('User edited.', 200);
    }

    public function destroy(int $user_id, DeleteUserAction $action)
    {
        $action->handle()->run(); // OR $action->handle()->delete($action->getValidated());
        return response()->json('User deleted.', 204);
    }
}
```

#### Create
```php
use LumiteStudios\Action\AbstractAction;
use LumiteStudios\Action\Concerns\HandleErrorsTrait;
use LumiteStudios\Action\Interfaces\CreateInterface;
use LumiteStudios\Action\Concerns\HandleRequestTrait;

class CreateUserAction extends AbstractAction implements CreateInterface
{
	use HandleErrorsTrait;
	use HandleRequestTrait;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            //
        ];
    }

    /**
     * Create a new resource.
     *
     * @param array $attributes 	An array of attributes.
     * @return mixed
     */
    public function create(array $attributes)
    {
        $user = new User();

        // handle creating a new user

        return $user->id;
    }

    /**
     * Get any associated errors.
     *
     * @param array $attributes 	An array of attributes.
     * @return void
     */
    protected function errors(array $attributes): void
    {
        //
    }
}
```

#### Edit
```php
use LumiteStudios\Action\AbstractAction;
use LumiteStudios\Action\Interfaces\EditInterface;
use LumiteStudios\Action\Concerns\HandleErrorsTrait;
use LumiteStudios\Action\Concerns\HandleRequestTrait;

class EditUserAction extends AbstractAction implements EditInterface
{
	use HandleErrorsTrait;
	use HandleRequestTrait;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            // can be fetched from the route parameters
            // e.g. Route::patch('/users/{user_id});
            'user_id' => ['required', 'exists:users,id'],
        ];
    }

    /**
     * Edit a resource.
     *
     * @param array $attributes 	An array of attributes.
     * @return mixed
     */
    public function edit(array $attributes)
    {
        $user = $this->fetchUser($attributes['user_id']);

        // handle editing the user

        return $user->id;
    }

    /**
     * Fetch a user using an id.
     *
     * @param int $user_id
     * @return User
     */
    private function fetchUser(int $user_id): User
    {
        return User::where('id', '=', $user_id)->first();
    }

    /**
     * Get any associated errors.
     *
     * @param array $attributes 	An array of attributes.
     * @return void
     */
    protected function errors(array $attributes): void
    {
        //
    }
}
```

#### Delete
```php
use LumiteStudios\Action\AbstractAction;
use LumiteStudios\Action\Concerns\HandleErrorsTrait;
use LumiteStudios\Action\Interfaces\DeleteInterface;
use LumiteStudios\Action\Concerns\HandleRequestTrait;

class DeleteUserAction extends AbstractAction implements DeleteInterface
{
	use HandleErrorsTrait;
	use HandleRequestTrait;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            // can be fetched from the route parameters
            // e.g. Route::patch('/users/{user_id});
            'user_id' => ['required', 'exists:users,id'],
        ];
    }

    /**
     * Delete a resource.
     *
     * @param array $attributes 	An array of attributes.
     * @return mixed
     */
    public function delete(array $attributes)
    {
        $user = $this->fetchUser($attributes['user_id']);
        $user->delete();

        return true;
    }

    /**
     * Fetch a user using an id.
     *
     * @param int $user_id
     * @return User
     */
    private function fetchUser(int $user_id): User
    {
        return User::where('id', '=', $user_id)->first();
    }

    /**
     * Get any associated errors.
     *
     * @param array $attributes 	An array of attributes.
     * @return void
     */
    protected function errors(array $attributes): void
    {
        //
    }
}
```

#### Save
```php
use LumiteStudios\Action\AbstractAction;
use LumiteStudios\Action\Concerns\HandleErrorsTrait;
use LumiteStudios\Action\Interfaces\SaveInterface;
use LumiteStudios\Action\Concerns\HandleRequestTrait;

class SaveUsersAction extends AbstractAction implements SaveInterface
{
	use HandleErrorsTrait;
	use HandleRequestTrait;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            //
        ];
    }

    /**
	 * Save changes to a resource.
	 *
	 * @param array<mixed> $attributes 	An array of attributes.
	 * @return mixed
	 */
	public function save(array $attributes)
    {
        return true;
    }

    /**
     * Get any associated errors.
     *
     * @param array $attributes 	An array of attributes.
     * @return void
     */
    protected function errors(array $attributes): void
    {
        //
    }
}
```
