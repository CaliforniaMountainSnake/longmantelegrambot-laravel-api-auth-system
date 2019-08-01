# longmantelegrambot-laravel-auth-system
This is the laravel api auth system intended for the longman/telegram-bot library.
Important! This library uses the [californiamountainsnake/simple-laravel-auth-system](https://github.com/CaliforniaMountainSnake/simple-laravel-auth-system) library, you must install and config one first.

## Install:
### Require this package with Composer
Install this package through [Composer](https://getcomposer.org/).
Edit your project's `composer.json` file to require `californiamountainsnake/longmantelegrambot-laravel-api-auth-system`:
```json
{
    "name": "yourproject/yourproject",
    "type": "project",
    "require": {
        "php": "^7.2",
        "californiamountainsnake/longmantelegrambot-laravel-api-auth-system": "*"
    }
}
```
and run `composer update`

### or
run this command in your command line:
```bash
composer require californiamountainsnake/longmantelegrambot-laravel-api-auth-system
```

## Usage:
1. Extend the abstract classes `AuthTelegrambotUserEntity` and `AuthTelegrambotUserRepository`.
2. Include the `AuthBotAccessUtils`, `AuthApiUtils` and `AuthUserUtils` traits into your base Command class and realise the abstract methods. Set the correct return type hints in the phpdoc for the methods `getUserEntity()`, `getUserRole()` and `getUserAccountType()`.
```php
<?php
class BaseCommand extends Command {
    use AuthBotAccessUtils;
    use AuthApiUtils;
    use AuthUserUtils;
    
    /**
     * Get user's entity. Just specify return type hint.
     * @return UserEntity|null
     */
    protected function getUserEntity(): ?AuthUserEntity
    {
        return $this->userUtilsGetUserEntity();
    }

    /**
     * Get user's role. Just specify return type hint.
     * @return UserRoleEnum
     */
    protected function getUserRole(): AuthUserRoleEnum
    {
        return $this->userUtilsGetUserRole();
    }

    /**
     * Get user's account type. Just specify return type hint.
     * @return UserAccountTypeEnum
     */
    protected function getUserAccountType(): AuthUserAccountTypeEnum
    {
        return $this->userUtilsGetUserAccountType();
    }
}
```
3. Call `$this->initTelegramParams()`, `$this->reInitUserParams()` and then `$this->assertUserHasAccessToMainRoute()` in the `preExecute()` method and handle the exceptions:
```php
<?php
class BaseCommand extends Command {
    use AuthBotAccessUtils;
    use AuthApiUtils;
    use AuthUserUtils;
    
    public function preExecute(): ServerResponse
    {
        $this->initTelegramParams();
        $this->reInitUserParams();

        try {
            // If user try to execute a wrong command, the exception will throw.
            $this->assertUserHasAccessToMainRoute($this->getUserEntity());
            
            return parent::preExecute();
        } catch (ApiProxyException $e) {
            // handle the error!
        } catch (UserRoleNotEqualsException $e) {
            // handle the error!
        } catch (UserAccountTypeNotEqualsException $e) {
            // handle the error!
        } catch (\Throwable $t) {
            // A good idea is to handle other exceptions.
        }
    }
}
```
4. Use AuthApiUtils's methods to execute queries to your laravel api:
```php
<?php
class MyCommand extends BaseCommand {
    
    public function execute (): ServerResponse {
        $json = $this->callApiNotAuth($this->getMainRoute(), [
            'email' => 'new@email.com',
        ])->getContent();
    }
}
``` 
5. If you want, you can realise the ApiProxyInterface and process api queries as you prefer.
