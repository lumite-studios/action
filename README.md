# Action

## Documentation

### Install
```bash
composer require lumite-studios/action
```

### Usage
The `\LumiteStudios\Action\Action` class can be used to simplify creating, editing, and deleting various resources.

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
        // handle creating a new user
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
