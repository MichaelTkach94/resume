<?php

class AuthService
{
    private LoyaltyService $loyaltyService;

    public function __construct()
    {
        $this->loyaltyService = new LoyaltyService();
    }

    /**
     * @throws CustomerNotFoundException
     * @throws UserNotFoundException
     */
    public function sendAuthorizationCode(GetVerificationCodeDTO $dto): void
    {
        $this->loyaltyService->checkBeforeAuthorize($dto);
        $this->loyaltyService->sendAuthorizationCode($dto);
    }

    /**
     * @throws ConfirmationException
     * @throws CustomerNotFoundException
     * @throws UserNotFoundException
     */
    public function authorize(ConfirmVerificationCodeDTO $dto): void
    {
        $this->loyaltyService->checkBeforeAuthorize($dto);
        $bitrixUserId = $this->loyaltyService
            ->confirmPhoneBeforeAuthorize($dto)
            ->getBitrixUserId();
        $this->authorizeByUserId($bitrixUserId);
    }

    /**
     * @throws UserAlreadyAddedException
     */
    public function sendRegistrationCode(GetVerificationCodeDTO $dto): VerificationDTO
    {
        $this->loyaltyService->checkBeforeRegister($dto);
        $this->loyaltyService->sendRegistrationCode($dto);

        return (new VerificationDTO())
            ->setIsVerified(false)
            ->setHasLoyaltyAccount(true)
            ->setShowPhoneCodeField(true);
    }

    /**
     * @throws ConfirmationException
     * @throws UserAlreadyAddedException
     */
    public function confirmPhoneBeforeRegister(ConfirmVerificationCodeDTO $dto): VerificationDTO
    {
        $this->loyaltyService->checkBeforeRegister($dto);
        $this->loyaltyService->confirmPhoneBeforeRegisterFirstStep($dto);

        return (new VerificationDTO())
            ->setIsVerified(true)
            ->setHasLoyaltyAccount(true)
            ->setShowPhoneCodeField(false);
    }

    /**
     * @throws ValidationException
     * @throws UserAlreadyAddedException
     * @throws UserNotFoundException
     * @throws ValidateFieldException
     */
    public function register(OnRegisterDTO $dto): void
    {
        $this->loyaltyService->checkBeforeRegister($dto);
        $this->loyaltyService->confirmPhoneBeforeRegisterSecondStep($dto);
        $this->registerInBitrix($dto);
        $bitrixUserId = $this->loyaltyService
            ->register($dto)
            ->getBitrixUserId();
        $this->authorizeByUserId($bitrixUserId);
    }

    private function authorizeByUserId(int $userId): void
    {
        $isAuthorized = (new CUser())->Authorize($userId);
        if (!$isAuthorized) {
            throw new UserNotAuthorizedException("Ошибка авторизации пользователя с ID {$userId}");
        }
    }

    /**
     * @throws ValidateFieldException
     */
    private function registerInBitrix(OnRegisterDTO $dto): void
    {
        $user = (new UserRepository())->getByEmail($dto->getEmail()->getRaw());
        if (!empty($user)) {
            throw new ValidateFieldException(FieldError::EMAIL, 'Email уже занят!');
        }

        $user = (new UserBuilder())
            ->setEmail($dto->getEmail()->getRaw())
            ->setName($dto->getName()->getFirstName())
            ->setSecondName($dto->getName()->getPatronymic())
            ->setLastName($dto->getName()->getLastName())
            ->setPhone($dto->getPhone()->getForDb())
            ->setGender($dto->getSex()->getRaw())
            ->setPassword(Random::getStringByAlphabet(32, Random::ALPHABET_ALL))
            ->build();

        $id = (new UserRepository())->add($user);

        $dto->setBitrixUserId($id);
    }
}
