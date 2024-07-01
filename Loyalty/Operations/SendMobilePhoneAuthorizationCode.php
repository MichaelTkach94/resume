<?php

class SendMobilePhoneAuthorizationCode extends CustomEvent
{
    private IdentifyInterface $dto;

    public function __construct(IdentifyInterface $dto)
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

        return $operationDTO;
    }

    protected function getOperationName(): string
    {
        $alias = isDev() ? 'sendAuthorizationCodeTEST' : 'sendAuthorizationCode';
        return Options::getOperationName($alias);
    }

    protected function addToQueue(): bool
    {
        return false;
    }

    protected function getResponseType(): string
    {
        return MindboxCustomerProcessingStatusResponse::class;
    }
}
