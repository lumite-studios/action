# lumite-studios/action

![PHP ^8.0](https://img.shields.io/badge/PHP-%5E8.0-787CB5?style=for-the-badge&logo=php)
[![codecov](https://img.shields.io/codecov/c/github/lumite-studios/action/main?label=codecov&style=for-the-badge&token=JLOQF31K23)](https://codecov.io/gh/lumite-studios/action)

## Documentation

### Installation
```bash
composer require lumite-studios/action
```

### Testing
``` bash
composer test
```

### Usage
The `\LumiteStudios\Action\Action` class can be used to simplify running various actions.

#### Example Action
```php
use LumiteStudios\Action\Action;

class CreateUser extends Action
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return !auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ];
    }

    /**
     * Handle the action.
     *
     * @param \Illuminate\Http\Request $request
     * @return User
     */
    public function handle(Request $request)
    {
        return User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
    }
}
```
