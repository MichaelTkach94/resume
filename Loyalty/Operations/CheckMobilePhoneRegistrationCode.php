<?php

class CheckMobilePhoneRegistrationCode extends CustomEvent
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

        $smsConfirmationDTO = new SmsConfirmationRequestDTO();
        $smsConfirmationDTO->setCode($this->dto->getAuthenticationCode()->getRaw());

        $operationDTO = new OperationDTO();
        $operationDTO->setCustomer($customerRequestDTO);
        $operationDTO->setSmsConfirmation($smsConfirmationDTO);

        return $operationDTO;
    }

    protected function getOperationName(): string
    {
        return Options::getOperationName('checkRegistrationCode');
    }

    protected function addToQueue(): bool
    {
        return false;
    }

    protected function getResponseType(): string
    {
        return MindboxSmsConfirmationResponse::class;
    }
}
