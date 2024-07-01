<?php

class AuthController extends Controller
{
    private Logger $logger;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);

        $this->logger = new Logger('/loyalty/errors/' . date('Y-m-d') . '.log');
    }

    public function getVerificationCodeAction(string $phone): AjaxResponse
    {
        $fieldsErrors = [];

        try {
            $dto = (new GetVerificationCodeDTO())
                ->setPhone(new Phone($phone));
            (new AuthService)->sendAuthorizationCode($dto);
        } catch (ValidateFieldException $e) {
            $fieldsErrors[] = new FieldError($e->getLogicalName(), $e->getMessage());
        } catch (UserNotFoundException|CustomerNotFoundException $e) {
            $fieldsErrors[] = new FieldError(FieldError::PHONE, 'Требуется регистрация!');
        } catch (Throwable $e) {
            $fieldsErrors[] = new FieldError(FieldError::PHONE, 'Неизвестная ошибка!');
            $this->logger->error($e);
        } finally {
            if (isset($e)) {
                $this->logger->debug($e->getMessage());
            }
        }

        return !empty($fieldsErrors)
            ? AjaxResponse::getFailureResponse()->withPayload(['fieldsErrors' => $fieldsErrors])
            : AjaxResponse::getSuccessResponse();
    }

    public function resendVerificationCodeAction(string $phone): AjaxResponse
    {
        return $this->getVerificationCodeAction($phone);
    }

    public function confirmVerificationCodeAction(string $phone, string $code): AjaxResponse
    {
        $fieldsErrors = [];

        try {
            $dto = (new ConfirmVerificationCodeDTO())
                ->setPhone(new Phone($phone))
                ->setAuthenticationCode(new AuthenticationCode($code));
            (new AuthService)->authorize($dto);
        } catch (ValidateFieldException $e) {
            $fieldsErrors[] = new FieldError($e->getLogicalName(), $e->getMessage());
        } catch (UserNotFoundException|CustomerNotFoundException $e) {
            $fieldsErrors[] = new FieldError(FieldError::PHONE, 'Требуется регистрация!');
        } catch (ConfirmationException $e) {
            $fieldsErrors[] = new FieldError(FieldError::CODE, 'Неверный код подтверждения!');
        } catch (Throwable $e) {
            $fieldsErrors[] = new FieldError(FieldError::PHONE, 'Неизвестная ошибка!');
            $this->logger->error($e);
        } finally {
            if (isset($e)) {
                $this->logger->debug($e->getMessage());
            }
        }

        return !empty($fieldsErrors)
            ? AjaxResponse::getFailureResponse()->withPayload(['fieldsErrors' => $fieldsErrors])
            : AjaxResponse::getSuccessResponse()->withPayload(['location' => PageHelper::getHomePageUrl()]);
    }

    protected function getDefaultPreFilters(): array
    {
        return [
            new Csrf(),
            new HttpMethod([HttpMethod::METHOD_POST]),
        ];
    }
}
