# Action

## Documentation

### Install
```bash
composer require lumite-studios/action
```

### Usage
The `\LumiteStudios\Action\Action` class can be used to simplify creating, editing, and deleting various resources.

#### Controller
```php
public function UserController extends Controller
{
    public function store(CreateUserAction $action)
    {
        $state = $action->handle();
        $user = User::where('id', '=', $state)->first();
        return response()->json('User created.', 201);
    }

    public function update(int $user_id, EditUserAction $action)
    {
        $state = $action->handle();
        $user = User::where('id', '=', $state)->first();
        return response()->json('User edited.', 200);
    }

    public function destroy(int $user_id, DeleteUserAction $action)
    {
        $action->handle();
        return response()->json('User deleted.', 204);
    }
}
```

#### Create
```php
use LumiteStudios\Action\Action;
use LumiteStudios\Action\Interfaces\ICreateInterface;
use LumiteStudios\Action\Interfaces\IRequestInterface;

class CreateUserAction extends Action implements ICreateInterface, IRequestInterface
{
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
use LumiteStudios\Action\Action;
use LumiteStudios\Action\Interfaces\IEditInterface;
use LumiteStudios\Action\Interfaces\IRequestInterface;

class EditUserAction extends Action implements IEditInterface, IRequestInterface
{
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
use LumiteStudios\Action\Action;
use LumiteStudios\Action\Interfaces\IDeleteInterface;
use LumiteStudios\Action\Interfaces\IRequestInterface;

class DeleteUserAction extends Action implements IDeleteInterface, IRequestInterface
{
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
	 * @return boolean
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
