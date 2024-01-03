<?php

namespace common\components\dto\payment\banks\requests\sbp;

class GetDTO
{
    public function __construct(
        protected string $orderNumber
    )
    {
    }

    public function getAlfaData(): array
    {
        /**
         * Возможные поля для передачи в запросе
         * @property string $mdOrder Номер заказа в системе платёжного шлюза.
         * @property string $qrHeight Высота QR-кода в пикселах. Укажите, если требуется renderedQR. Минимальное значение: 10. Максимальное значение: 1000
         * @property string $qrWidth Ширина QR-кода. Укажите, если требуется renderedQR. Минимальное значение: 10. Максимальное значение: 1000.
         * @property string $qrFormat Возможные значения: matrix - вернёт матрицу из нулей и единиц; image - вернёт картинку в base64.
         */
        return [
            'mdOrder' => $this->orderNumber
        ];
    }
}