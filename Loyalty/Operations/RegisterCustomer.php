<?php

class RegisterCustomer extends CustomEvent
{
    private RegistrationInterface $dto;

    public function __construct(RegistrationInterface $dto)
    {
        parent::__construct();

        $this->dto = $dto;
    }

    protected function getMethod(): string
    {
        return HttpClient::HTTP_POST;
    }

    protected function getOperationDTO(): OperationDTO
    {
        $customerRequestDTO = new CustomerRequestDTO();
        $customerRequestDTO->setMobilePhone($this->dto->getPhone()->getForMindbox());
        $customerRequestDTO->setEmail($this->dto->getEmail()->getRaw());
        $customerRequestDTO->setFirstName($this->dto->getName()->getFirstName());
        $customerRequestDTO->setMiddleName($this->dto->getName()->getPatronymic());
        $customerRequestDTO->setLastName($this->dto->getName()->getLastName());
        $customerRequestDTO->setField('sex', $this->dto->getSex()->getForMindbox());
        $customerRequestDTO->setId(CustomEvent::getNameForBxUserIdField(), $this->dto->getBitrixUserId());

        if ($this->dto->isSubscribe()) {
            $subscriptionRequestDTO = new SubscriptionRequestDTO();
            $subscriptionRequestDTO->setPointOfContact(self::POINT_OF_CONTACT_PHONE);
            $subscriptionRequestDTO->setBrand(Options::getModuleOption('BRAND'));
            $subscriptionRequestDTO->setIsSubscribed(true);
            $customerRequestDTO->setSubscriptions([$subscriptionRequestDTO]);
        }

        $operationDTO = new OperationDTO();
        $operationDTO->setCustomer($customerRequestDTO);

        return $operationDTO;
    }

    protected function getOperationName(): string
    {
        $alias = isDev() ? 'registerByPhoneTEST' : 'registerByPhone';
        return Options::getOperationName($alias);
    }

    protected function getResponseType(): string
    {
        return MindboxCustomerResponse::class;
    }

    protected function addToQueue(): bool
    {
        return false;
    }
}
