<?php

class UnitConversionController
{
  private $model;

  public function __construct(UnitConversion $model)
  {
    $this->model = $model;
  }

  public function convert(Request $request)
  {
    $requestData = $request->getData();

    if (!isset($requestData['value'], $requestData['from_unit'], $requestData['to_unit'])) {
      $response = [
        'error' => 'Invalid parameters'
      ];
      http_response_code(400);
      echo json_encode($response);
      return;
    }

    $value = $requestData['value'];
    $fromUnit = strtolower($requestData['from_unit']);
    $toUnit = strtolower($requestData['to_unit']);

    if (!is_numeric($value) && !is_array($value)) {
      $response = [
        'error' => 'The value must be a number'
      ];
      http_response_code(400);
      echo json_encode($response);
      return;
    }

    if (is_array($value)) {
      $results = $this->model->convertMultiple($value, $fromUnit, $toUnit);
      $response = [];

      foreach ($results as $result) {
        if ($result !== null) {
          $response[] = [
            'result' => $result
          ];
        } else {
          $response[] = [
            'error' => 'Conversion not supported'
          ];
        }
      }
    } else {
      $result = $this->model->convert($value, $fromUnit, $toUnit);

      if ($result !== null) {
        $response = [
          'result' => $result
        ];
      } else {
        $response = [
          'error' => 'The unit is not available to convert'
        ];
      }
    }

    http_response_code(200);
    echo json_encode($response);
  }

  public function getUnitList()
  {
    $unitList = $this->model->getUnitList();

    http_response_code(200);
    echo json_encode($unitList);
  }
}
