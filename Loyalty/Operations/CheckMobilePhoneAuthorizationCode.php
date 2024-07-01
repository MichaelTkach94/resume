<?php

class CheckMobilePhoneAuthorizationCode extends CustomEvent
{
    private AuthenticationInterface $dto;

    public function __construct(AuthenticationInterface $dto)
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

        $operationDTO = new OperationDTO();
        $operationDTO->setCustomer($customerRequestDTO);
        $operationDTO->setAuthentificationCode($this->dto->getAuthenticationCode()->getRaw());

        return $operationDTO;
    }

    protected function getOperationName(): string
    {
        return Options::getOperationName('checkAuthorizationCode');
    }

    protected function addToQueue(): bool
    {
        return false;
    }

    protected function getResponseType(): string
    {
        return MindboxCustomerIdentityResponse::class;
    }
}
