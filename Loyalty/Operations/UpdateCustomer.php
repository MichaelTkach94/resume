<?php

class UpdateCustomer extends CustomEvent
{
    private UpdateInterface $dto;

    public function __construct(UpdateInterface $dto)
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
        $customerRequestDTO->setId(CustomEvent::getNameForBxUserIdField(), $this->dto->getBitrixUserId());
        $customerRequestDTO->setMobilePhone($this->dto->getPhone()->getForMindbox());

        $operationDTO = new OperationDTO();
        $operationDTO->setCustomer($customerRequestDTO);

        return $operationDTO;
    }

    protected function getOperationName(): string
    {
        return Options::getOperationName('updatePhone');
    }

    protected function addToQueue(): bool
    {
        return false;
    }
}
