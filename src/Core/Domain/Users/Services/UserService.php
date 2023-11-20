<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Domain\Users\Services;

use ToniLiesche\Roadrunner\Core\Application\Library\Enums\ErrorCode;
use ToniLiesche\Roadrunner\Core\Application\Library\Exceptions\PasswordVerificationFailedException;
use ToniLiesche\Roadrunner\Core\Application\Security\Services\HashService;
use ToniLiesche\Roadrunner\Core\Domain\Login\Models\LoginPayload;
use ToniLiesche\Roadrunner\Core\Domain\Users\Interfaces\UserDataProviderInterface;
use ToniLiesche\Roadrunner\Core\Domain\Users\Interfaces\UserServiceInterface;
use ToniLiesche\Roadrunner\Core\Domain\Users\Models\User;
use ToniLiesche\Roadrunner\Infrastructure\Http\Exceptions\InternalServerErrorException;
use ToniLiesche\Roadrunner\Infrastructure\Http\Exceptions\NotFoundException;
use ToniLiesche\Roadrunner\Infrastructure\Shared\Exceptions\DataMappingException;
use ToniLiesche\Roadrunner\Infrastructure\Shared\Exceptions\DataProviderException;
use ToniLiesche\Roadrunner\Infrastructure\Shared\Exceptions\ItemNotFoundException;

final class UserService implements UserServiceInterface
{
    private User $user;

    public function __construct(private readonly UserDataProviderInterface $userDataProvider)
    {
    }

    public function getUser(): User
    {
        return $this->user ?? new User(['id' => -1, 'username' => 'anonymous', 'name' => 'Anonymous User', 'password' => '']);
    }

    /**
     * @throws InternalServerErrorException
     * @throws NotFoundException
     */
    public function fetchUser(int $userId): User
    {
        try {
            return $this->userDataProvider->fetchUser($userId);
        } catch (DataMappingException|DataProviderException $ex) {
            throw new InternalServerErrorException('Could not retrieve user from data source.', $ex);
        } catch (ItemNotFoundException $ex) {
            throw new NotFoundException('Could not find requested user.', $ex, ErrorCode::E_USER_NOT_FOUND);
        }
    }

    /**
     * @throws InternalServerErrorException
     * @throws NotFoundException
     * @throws PasswordVerificationFailedException
     */
    public function validateLogin(LoginPayload $loginPayload): void
    {
        try {
            $user = $this->userDataProvider->fetchUserByUsername($loginPayload->getUsername());
            $this->user = $user;
        } catch (DataMappingException|DataProviderException $ex) {
            throw new InternalServerErrorException('Could not retrieve user from data source.', $ex);
        } catch (ItemNotFoundException $ex) {
            throw new NotFoundException('Could not find requested user.', $ex);
        }

        HashService::validatePassword($loginPayload->getPassword(), $user->getPassword());
    }
}
