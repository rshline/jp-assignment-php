<?php

class Router
{
  private $routes = [];

  public function addRoute($path, $method, $handler)
  {
    $this->routes[$path][$method] = $handler;
  }

  public function handle()
  {
    $request = new Request();
    $path = $request->getPath();
    $method = $request->getMethod();

    if (isset($this->routes[$path][$method])) {
      $handler = $this->routes[$path][$method];
      call_user_func($handler, $request);
    } else {
      http_response_code(404);
      echo json_encode(['error' => 'Endpoint not found']);
    }
  }
}

class Request
{
  private $path;
  private $method;
  private $body;
  private $data;

  public function __construct()
  {
    $this->path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $this->method = $_SERVER['REQUEST_METHOD'];
    $this->body = file_get_contents('php://input');

    $this->data = $this->parseRequestData();
  }

  public function getPath()
  {
    $basePath = str_replace('/index.php', '', $_SERVER['PHP_SELF']);
    $requestPath = $_SERVER['REQUEST_URI'];
    $path = str_replace($basePath, '', $requestPath);
    $path = rtrim($path, '/');

    $directory = pathinfo($_SERVER['PHP_SELF'], PATHINFO_DIRNAME);
    $directory = rtrim($directory, '/');
    $directorySegments = explode('/', $directory);
    $directoryName = end($directorySegments);

    $pathSegments = explode('/', $path);
    $key = array_search($directoryName, $pathSegments);
    if ($key !== false && isset($pathSegments[$key + 1])) {
      return '/' . $pathSegments[$key + 1];
    }

    return '';
  }


  public function getMethod()
  {
    return $this->method;
  }

  public function getBody()
  {
    return $this->body;
  }

  private function parseRequestData()
  {
    $contentType = $_SERVER['CONTENT_TYPE'];

    if ($contentType === 'application/json') {
      return json_decode($this->body, true);
    } elseif (
      strpos($contentType, 'multipart/form-data') !== false ||
      strpos($contentType, 'application/x-www-form-urlencoded') !== false
    ) {
      parse_str($this->body, $formData);
      return $formData;
    }

    return [];
  }

  public function getData()
  {
    return $this->data;
  }
}

class UnitConversion
{
  public function convert($value, $fromUnit, $toUnit)
  {
    $converter = $this->getConverter($fromUnit);
    if ($converter !== null) {
      return $converter->convert($value, $fromUnit, $toUnit);
    } else {
      return null;
    }
  }

  public function convertMultiple($values, $fromUnit, $toUnit)
  {
    $results = [];

    foreach ($values as $value) {
      $result = $this->convert($value, $fromUnit, $toUnit);
      $results[] = $result;
    }

    return $results;
  }

  public function getUnitList()
  {
    return [
      "temperature_units" => [
        "celsius",
        "fahrenheit",
        "kelvin"
      ],
      "length_units" => [
        "meter",
        "kilometer",
        "centimeter",
        "millimeter",
        "micrometer",
        "nanometer",
        "mile",
        "yard",
        "foot",
        "inch",
        "light_year"
      ],
      "area_units" => [
        "square_meter",
        "square_kilometer",
        "square_centimeter",
        "square_millimeter",
        "square_micrometer",
        "hectare",
        "square_mile",
        "square_yard",
        "square_foot",
        "square_inch",
        "acre"
      ],
      "volume_units" => [
        "cubic_meter",
        "liter",
        "mililiter",
        "cubic_mile",
        "cubic_yard",
        "cubic_foot",
        "cubic_inch"
      ],
      "weight_units" => [
        "kilogram",
        "gram",
        "miligram",
        "ton",
        "pound",
        "ounce",
        "carrat",
        "atomic_mass_unit"
      ]
    ];
  }

  private function getConverter($unit)
  {

    $temperatureUnits = [
      'celsius',
      'fahrenheit',
      'kelvin'
    ];

    $lengthUnits = [
      'meter',
      'kilometer',
      'centimeter',
      'millimeter',
      'micrometer',
      'nanometer',
      'mile',
      'yard',
      'foot',
      'inch',
      'light_year'
    ];

    $areaUnits = [
      'square_meter',
      'square_kilometer',
      'square_centimeter',
      'square_miilimeter',
      'square_micrometer',
      'hectare',
      'square_mile',
      'square_yard',
      'square_foot',
      'square_inch',
      'acre'
    ];

    $volumeUnits = [
      'cubic_meter',
      'liter',
      'mililiter',
      'cubic_mile',
      'cubic_yard',
      'cubic_foot',
      'cubic_inch'
    ];

    $weightUnits = [
      'kilogram',
      'gram',
      'miligram',
      'ton',
      'pound',
      'ounce',
      'carrat',
      'atomic mass unit'
    ];

    if (in_array($unit, $temperatureUnits)) {
      $converter = new TemperatureConverter();
    } elseif (in_array($unit, $lengthUnits)) {
      $converter = new LengthConverter();
    } elseif (in_array($unit, $areaUnits)) {
      $converter = new AreaConverter();
    } elseif (in_array($unit, $volumeUnits)) {
      $converter = new VolumeConverter();
    } elseif (in_array($unit, $weightUnits)) {
      $converter = new WeightConverter();
    } else {
      $converter = null;
    }

    return $converter;
  }
}

class TemperatureConverter extends UnitConversion
{
  public function convert($value, $fromUnit, $toUnit)
  {
    if ($fromUnit === 'celsius') {
      $result = $this->convertFromCelsius($value, $toUnit);
    } elseif ($fromUnit === 'kelvin') {
      $result = $this->convertFromKelvin($value, $toUnit);
    } elseif ($fromUnit === 'fahrenheit') {
      $result = $this->convertFromFahrenheit($value, $toUnit);
    } else {
      $result = null;
    }

    return $result;
  }

  public function convertFromCelsius($value, $toUnit)
  {
    $conversionTable = [
      'celcius' => function ($value) {
        return $value;
      },
      'fahrenheit' => function ($value) {
        return ($value * 9 / 5) + 32;
      },
      'kelvin' => function ($value) {
        return $value + 273.15;
      },
    ];

    if (isset($conversionTable[$toUnit])) {
      $conversionFunction = $conversionTable[$toUnit];
      $result = $conversionFunction($value);
    } else {
      return null;
    }

    return $result;
  }

  public function convertFromKelvin($value, $toUnit)
  {
    $conversionTable = [
      'celsius' => function ($value) {
        return $value - 273.15;
      },
      'kelvin' => function ($value) {
        return $value;
      },
      'fahrenheit' => function ($value) {
        return ($value - 273.15) * 9 / 5 + 32;
      },
    ];

    if (isset($conversionTable[$toUnit])) {
      $conversionFunction = $conversionTable[$toUnit];
      $result = $conversionFunction($value);
    } else {
      return null;
    }

    return $result;
  }

  public function convertFromFahrenheit($value, $toUnit)
  {
    $conversionTable = [
      'celsius' => function ($value) {
        return ($value - 32) * 5 / 9;
      },
      'kelvin' => function ($value) {
        return ($value - 32) * 5 / 9 + 273.15;
      },
      'fahrenheit' => function ($value) {
        return $value;
      }
    ];

    if (isset($conversionTable[$toUnit])) {
      $conversionFunction = $conversionTable[$toUnit];
      $result = $conversionFunction($value);
    } else {
      return null;
    }

    return $result;
  }
}

class LengthConverter extends UnitConversion
{
  public function convert($value, $fromUnit, $toUnit)
  {
    if ($fromUnit === 'meter') {
      $result = $this->convertFromMeter($value, $toUnit);
    } elseif ($fromUnit === 'kilometer') {
      $result = $this->convertFromKilometer($value, $toUnit);
    } elseif ($fromUnit === 'centimeter') {
      $result = $this->convertFromCentimeter($value, $toUnit);
    } elseif ($fromUnit === 'millimeter') {
      $result = $this->convertFromMillimeter($value, $toUnit);
    } elseif ($fromUnit === 'micrometer') {
      $result = $this->convertFromMicrometer($value, $toUnit);
    } elseif ($fromUnit === 'nanometer') {
      $result = $this->convertFromNanometer($value, $toUnit);
    } elseif ($fromUnit === 'mile') {
      $result = $this->convertFromMile($value, $toUnit);
    } elseif ($fromUnit === 'yard') {
      $result = $this->convertFromYard($value, $toUnit);
    } elseif ($fromUnit === 'foot') {
      $result = $this->convertFromFoot($value, $toUnit);
    } elseif ($fromUnit === 'inch') {
      $result = $this->convertFromInch($value, $toUnit);
    } elseif ($fromUnit === 'light_year') {
      $result = $this->convertFromLightYear($value, $toUnit);
    } else {
      $result = null;
    }

    return $result;
  }

  public function convertFromMeter($value, $toUnit)
  {
    $conversionTable = [
      'meter' => function ($value) {
        return $value;
      },
      'kilometer' => function ($value) {
        return $value / 1000;
      },
      'centimeter' => function ($value) {
        return $value * 100;
      },
      'millimeter' => function ($value) {
        return $value * 1000;
      },
      'micrometer' => function ($value) {
        return $value * 1000000;
      },
      'nanometer' => function ($value) {
        return $value * 1000000;
      },
      'mile' => function ($value) {
        return $value / 1609.344;
      },
      'yard' => function ($value) {
        return $value * 1.0936133;
      },
      'foot' => function ($value) {
        return $value / 0.3048;
      },
      'inch' => function ($value) {
        return $value / 0.3048;
      },
      'light_year' => function ($value) {
        return $value / 9.4607E+15;
      }
    ];

    if (isset($conversionTable[$toUnit])) {
      $conversionFunction = $conversionTable[$toUnit];
      $result = $conversionFunction($value);
    } else {
      return null;
    }

    return $result;
  }

  public function convertFromKilometer($value, $toUnit)
  {
    $conversionTable = [
      'meter' => function ($value) {
        return $value * 1000;
      },
      'kilometer' => function ($value) {
        return $value;
      },
      'centimeter' => function ($value) {
        return $value * 100000;
      },
      'millimeter' => function ($value) {
        return $value * 1000000;
      },
      'micrometer' => function ($value) {
        return $value * 1000000000;
      },
      'nanometer' => function ($value) {
        return $value * 1000000000000;
      },
      'mile' => function ($value) {
        return $value * 0.621371;
      },
      'yard' => function ($value) {
        return $value * 1093.61;
      },
      'foot' => function ($value) {
        return $value * 3280.84;
      },
      'inch' => function ($value) {
        return $value * 39370.1;
      },
      'light_year' => function ($value) {
        return $value * 1.057e-13;
      }
    ];

    if (isset($conversionTable[$toUnit])) {
      $conversionFunction = $conversionTable[$toUnit];
      $result = $conversionFunction($value);
    } else {
      $result = null;
    }

    return $result;
  }

  public function convertFromCentimeter($value, $toUnit)
  {
    $conversionTable = [
      'meter' => function ($value) {
        return $value / 100;
      },
      'kilometer' => function ($value) {
        return $value / 100000;
      },
      'centimeter' => function ($value) {
        return $value;
      },
      'millimeter' => function ($value) {
        return $value * 10;
      },
      'micrometer' => function ($value) {
        return $value * 10000;
      },
      'nanometer' => function ($value) {
        return $value * 10000000;
      },
      'mile' => function ($value) {
        return $value / 160934.4;
      },
      'yard' => function ($value) {
        return $value / 91.44;
      },
      'foot' => function ($value) {
        return $value / 30.48;
      },
      'inch' => function ($value) {
        return $value / 2.54;
      },
      'light_year' => function ($value) {
        return $value / 9.461e+17;
      }
    ];

    if (isset($conversionTable[$toUnit])) {
      $conversionFunction = $conversionTable[$toUnit];
      $result = $conversionFunction($value);
    } else {
      $result = null;
    }

    return $result;
  }

  public function convertFromMillimeter($value, $toUnit)
  {
    $conversionTable = [
      'meter' => function ($value) {
        return $value / 1000;
      },
      'centimeter' => function ($value) {
        return $value / 10;
      },
      'millimeter' => function ($value) {
        return $value;
      },
      'micrometer' => function ($value) {
        return $value * 1000;
      },
      'nanometer' => function ($value) {
        return $value * 1000000;
      },
      'mile' => function ($value) {
        return $value / 1609344;
      },
      'yard' => function ($value) {
        return $value / 914.4;
      },
      'foot' => function ($value) {
        return $value / 304.8;
      },
      'inch' => function ($value) {
        return $value / 25.4;
      },
      'light_year' => function ($value) {
        return $value * 1.057e-16;
      }
    ];

    if (isset($conversionTable[$toUnit])) {
      $conversionFunction = $conversionTable[$toUnit];
      $result = $conversionFunction($value);
    } else {
      $result = null;
    }

    return $result;
  }

  public function convertFromMicrometer($value, $toUnit)
  {
    $conversionTable = [
      'meter' => function ($value) {
        return $value / 1000000;
      },
      'centimeter' => function ($value) {
        return $value / 10000;
      },
      'millimeter' => function ($value) {
        return $value / 1000;
      },
      'micrometer' => function ($value) {
        return $value;
      },
      'nanometer' => function ($value) {
        return $value * 1000;
      },
      'mile' => function ($value) {
        return $value / 1609344000;
      },
      'yard' => function ($value) {
        return $value / 914400;
      },
      'foot' => function ($value) {
        return $value / 304800;
      },
      'inch' => function ($value) {
        return $value / 25400;
      },
      'light_year' => function ($value) {
        return $value * 1.057e-19;
      }
    ];

    if (isset($conversionTable[$toUnit])) {
      $conversionFunction = $conversionTable[$toUnit];
      $result = $conversionFunction($value);
    } else {
      $result = null;
    }

    return $result;
  }

  public function convertFromNanometer($value, $toUnit)
  {
    $conversionTable = [
      'kilometer' => function ($value) {
        return $value / 1e12;
      },
      'meter' => function ($value) {
        return $value / 1e9;
      },
      'centimeter' => function ($value) {
        return $value / 1e7;
      },
      'millimeter' => function ($value) {
        return $value / 1e6;
      },
      'micrometer' => function ($value) {
        return $value / 1e3;
      },
      'nanometer' => function ($value) {
        return $value;
      },
      'mile' => function ($value) {
        return $value / 1.609e12;
      },
      'yard' => function ($value) {
        return $value / 9.144e8;
      },
      'foot' => function ($value) {
        return $value / 3.048e8;
      },
      'inch' => function ($value) {
        return $value / 2.54e7;
      },
      'light_year' => function ($value) {
        return $value * 1.057e-22;
      }
    ];

    if (isset($conversionTable[$toUnit])) {
      $conversionFunction = $conversionTable[$toUnit];
      $result = $conversionFunction($value);
    } else {
      $result = null;
    }

    return $result;
  }

  public function convertFromMile($value, $toUnit)
  {
    $conversionTable = [
      'meter' => function ($value) {
        return $value * 1609.34;
      },
      'centimeter' => function ($value) {
        return $value * 160934;
      },
      'millimeter' => function ($value) {
        return $value * 1.609e+6;
      },
      'micrometer' => function ($value) {
        return $value * 1.609e+9;
      },
      'nanometer' => function ($value) {
        return $value * 1.609e+12;
      },
      'mile' => function ($value) {
        return $value;
      },
      'yard' => function ($value) {
        return $value * 1760;
      },
      'foot' => function ($value) {
        return $value * 5280;
      },
      'inch' => function ($value) {
        return $value * 63360;
      },
      'light_year' => function ($value) {
        return $value * 5.878e-13;
      }
    ];

    if (isset($conversionTable[$toUnit])) {
      $conversionFunction = $conversionTable[$toUnit];
      $result = $conversionFunction($value);
    } else {
      $result = null;
    }

    return $result;
  }

  public function convertFromYard($value, $toUnit)
  {
    $conversionTable = [
      'meter' => function ($value) {
        return $value * 0.9144;
      },
      'centimeter' => function ($value) {
        return $value * 91.44;
      },
      'millimeter' => function ($value) {
        return $value * 914.4;
      },
      'micrometer' => function ($value) {
        return $value * 914400;
      },
      'nanometer' => function ($value) {
        return $value * 9.144e+8;
      },
      'mile' => function ($value) {
        return $value * 0.000568182;
      },
      'yard' => function ($value) {
        return $value;
      },
      'foot' => function ($value) {
        return $value * 3;
      },
      'inch' => function ($value) {
        return $value * 36;
      },
      'light_year' => function ($value) {
        return $value * 1.034e-16;
      }
    ];

    if (isset($conversionTable[$toUnit])) {
      $conversionFunction = $conversionTable[$toUnit];
      $result = $conversionFunction($value);
    } else {
      $result = null;
    }

    return $result;
  }

  public function convertFromFoot($value, $toUnit)
  {
    $conversionTable = [
      'meter' => function ($value) {
        return $value * 0.3048;
      },
      'centimeter' => function ($value) {
        return $value * 30.48;
      },
      'millimeter' => function ($value) {
        return $value * 304.8;
      },
      'micrometer' => function ($value) {
        return $value * 304800;
      },
      'nanometer' => function ($value) {
        return $value * 3.048e+8;
      },
      'mile' => function ($value) {
        return $value * 0.000189394;
      },
      'yard' => function ($value) {
        return $value * 0.333333;
      },
      'foot' => function ($value) {
        return $value;
      },
      'inch' => function ($value) {
        return $value * 12;
      },
      'light_year' => function ($value) {
        return $value * 1.093e-16;
      }
    ];

    if (isset($conversionTable[$toUnit])) {
      $conversionFunction = $conversionTable[$toUnit];
      $result = $conversionFunction($value);
    } else {
      $result = null;
    }

    return $result;
  }

  public function convertFromInch($value, $toUnit)
  {
    $conversionTable = [
      'meter' => function ($value) {
        return $value * 0.0254;
      },
      'centimeter' => function ($value) {
        return $value * 2.54;
      },
      'millimeter' => function ($value) {
        return $value * 25.4;
      },
      'micrometer' => function ($value) {
        return $value * 25400;
      },
      'nanometer' => function ($value) {
        return $value * 2.54e+7;
      },
      'mile' => function ($value) {
        return $value * 1.5783e-5;
      },
      'yard' => function ($value) {
        return $value * 0.0277778;
      },
      'foot' => function ($value) {
        return $value * 0.0833333;
      },
      'inch' => function ($value) {
        return $value;
      },
      'light_year' => function ($value) {
        return $value * 8.2315e-18;
      }
    ];

    if (isset($conversionTable[$toUnit])) {
      $conversionFunction = $conversionTable[$toUnit];
      $result = $conversionFunction($value);
    } else {
      $result = null;
    }

    return $result;
  }

  public function convertFromLightYear($value, $toUnit)
  {
    $conversionTable = [
      'meter' => function ($value) {
        return $value * 9.461e+15;
      },
      'centimeter' => function ($value) {
        return $value * 9.461e+17;
      },
      'millimeter' => function ($value) {
        return $value * 9.461e+18;
      },
      'micrometer' => function ($value) {
        return $value * 9.461e+21;
      },
      'nanometer' => function ($value) {
        return $value * 9.461e+24;
      },
      'mile' => function ($value) {
        return $value * 5.878e+12;
      },
      'yard' => function ($value) {
        return $value * 1.035e+16;
      },
      'foot' => function ($value) {
        return $value * 3.106e+16;
      },
      'inch' => function ($value) {
        return $value * 3.727e+17;
      },
      'light_year' => function ($value) {
        return $value;
      }
    ];

    if (isset($conversionTable[$toUnit])) {
      $conversionFunction = $conversionTable[$toUnit];
      $result = $conversionFunction($value);
    } else {
      $result = null;
    }

    return $result;
  }
}

class AreaConverter extends UnitConversion
{
  public function convert($value, $fromUnit, $toUnit)
  {
    if ($fromUnit === 'square_meter') {
      $result = $this->convertFromSquareMeter($value, $toUnit);
    } elseif ($fromUnit === 'square_kilometer') {
      $result = $this->convertFromSquareKilometer($value, $toUnit);
    } elseif ($fromUnit === 'square_centimeter') {
      $result = $this->convertFromSquareCentimeter($value, $toUnit);
    } elseif ($fromUnit === 'square_millimeter') {
      $result = $this->convertFromSquareMillimeter($value, $toUnit);
    } elseif ($fromUnit === 'square_micrometer') {
      $result = $this->convertFromSquareMicrometer($value, $toUnit);
    } elseif ($fromUnit === 'hectare') {
      $result = $this->convertFromHectare($value, $toUnit);
    } elseif ($fromUnit === 'square_mile') {
      $result = $this->convertFromSquareMile($value, $toUnit);
    } elseif ($fromUnit === 'square_yard') {
      $result = $this->convertFromSquareYard($value, $toUnit);
    } elseif ($fromUnit === 'square_foot') {
      $result = $this->convertFromSquareFoot($value, $toUnit);
    } elseif ($fromUnit === 'square_inch') {
      $result = $this->convertFromSquareInch($value, $toUnit);
    } elseif ($fromUnit === 'acre') {
      $result = $this->convertFromAcre($value, $toUnit);
    } else {
      $result = null;
    }

    return $result;
  }

  public function convertFromSquareMeter($value, $toUnit)
  {
    $conversionTable = [
      'square_meter' => function ($value) {
        return $value;
      },
      'square_kilometer' => function ($value) {
        return $value / 1e+6;
      },
      'square_centimeter' => function ($value) {
        return $value * 10000;
      },
      'square_millimeter' => function ($value) {
        return $value * 1e+6;
      },
      'square_micrometer' => function ($value) {
        return $value * 1e+12;
      },
      'hectare' => function ($value) {
        return $value / 10000;
      },
      'square_mile' => function ($value) {
        return $value / 2.59e+6;
      },
      'square_yard' => function ($value) {
        return $value * 1.196;
      },
      'square_foot' => function ($value) {
        return $value * 10.764;
      },
      'square_inch' => function ($value) {
        return $value * 1550;
      },
      'acre' => function ($value) {
        return $value / 4047;
      }
    ];

    if (isset($conversionTable[$toUnit])) {
      $conversionFunction = $conversionTable[$toUnit];
      $result = $conversionFunction($value);
    } else {
      $result = null;
    }

    return $result;
  }

  public function convertFromSquareKilometer($value, $toUnit)
  {
    $conversionTable = [
      'square_meter' => function ($value) {
        return $value * 1e+6;
      },
      'square_kilometer' => function ($value) {
        return $value;
      },
      'square_centimeter' => function ($value) {
        return $value * 1e+10;
      },
      'square_millimeter' => function ($value) {
        return $value * 1e+12;
      },
      'square_micrometer' => function ($value) {
        return $value * 1e+18;
      },
      'hectare' => function ($value) {
        return $value * 100;
      },
      'square_mile' => function ($value) {
        return $value / 2.59;
      },
      'square_yard' => function ($value) {
        return $value * 1.196e+6;
      },
      'square_foot' => function ($value) {
        return $value * 1.076e+7;
      },
      'square_inch' => function ($value) {
        return $value * 1.55e+9;
      },
      'acre' => function ($value) {
        return $value * 247.105;
      }
    ];

    if (isset($conversionTable[$toUnit])) {
      $conversionFunction = $conversionTable[$toUnit];
      $result = $conversionFunction($value);
    } else {
      $result = null;
    }

    return $result;
  }

  public function convertFromSquareCentimeter($value, $toUnit)
  {
    $conversionTable = [
      'square_meter' => function ($value) {
        return $value / 10000;
      },
      'square_kilometer' => function ($value) {
        return $value / 1e+10;
      },
      'square_centimeter' => function ($value) {
        return $value;
      },
      'square_millimeter' => function ($value) {
        return $value * 100;
      },
      'square_micrometer' => function ($value) {
        return $value * 1e+8;
      },
      'hectare' => function ($value) {
        return $value / 1e+8;
      },
      'square_mile' => function ($value) {
        return $value / 2.59e+10;
      },
      'square_yard' => function ($value) {
        return $value / 8361;
      },
      'square_foot' => function ($value) {
        return $value / 929;
      },
      'square_inch' => function ($value) {
        return $value / 6.452;
      },
      'acre' => function ($value) {
        return $value / 4.047e+7;
      }
    ];

    if (isset($conversionTable[$toUnit])) {
      $conversionFunction = $conversionTable[$toUnit];
      $result = $conversionFunction($value);
    } else {
      $result = null;
    }

    return $result;
  }

  public function convertFromSquareMillimeter($value, $toUnit)
  {
    $conversionTable = [
      'square_meter' => function ($value) {
        return $value / 1e+6;
      },
      'square_kilometer' => function ($value) {
        return $value / 1e+12;
      },
      'square_centimeter' => function ($value) {
        return $value / 100;
      },
      'square_millimeter' => function ($value) {
        return $value;
      },
      'square_micrometer' => function ($value) {
        return $value * 1e+6;
      },
      'hectare' => function ($value) {
        return $value / 1e+10;
      },
      'square_mile' => function ($value) {
        return $value / 2.59e+12;
      },
      'square_yard' => function ($value) {
        return $value / 836127;
      },
      'square_foot' => function ($value) {
        return $value / 92903;
      },
      'square_inch' => function ($value) {
        return $value / 645.16;
      },
      'acre' => function ($value) {
        return $value / 4.047e+9;
      }
    ];

    if (isset($conversionTable[$toUnit])) {
      $conversionFunction = $conversionTable[$toUnit];
      $result = $conversionFunction($value);
    } else {
      $result = null;
    }

    return $result;
  }

  public function convertFromSquareMicrometer($value, $toUnit)
  {
    $conversionTable = [
      'square_meter' => function ($value) {
        return $value / 1e+12;
      },
      'square_kilometer' => function ($value) {
        return $value / 1e+18;
      },
      'square_centimeter' => function ($value) {
        return $value / 10000;
      },
      'square_millimeter' => function ($value) {
        return $value / 1e+6;
      },
      'square_micrometer' => function ($value) {
        return $value;
      },
      'hectare' => function ($value) {
        return $value / 1e+16;
      },
      'square_mile' => function ($value) {
        return $value / 2.59e+18;
      },
      'square_yard' => function ($value) {
        return $value / 836127000000;
      },
      'square_foot' => function ($value) {
        return $value / 92903040000;
      },
      'square_inch' => function ($value) {
        return $value / 645160000;
      },
      'acre' => function ($value) {
        return $value / 4.047e+15;
      }
    ];

    if (isset($conversionTable[$toUnit])) {
      $conversionFunction = $conversionTable[$toUnit];
      $result = $conversionFunction($value);
    } else {
      $result = null;
    }

    return $result;
  }

  public function convertFromHectare($value, $toUnit)
  {
    $conversionTable = [
      'square_meter' => function ($value) {
        return $value * 10000;
      },
      'square_kilometer' => function ($value) {
        return $value / 100;
      },
      'square_centimeter' => function ($value) {
        return $value * 1e+8;
      },
      'square_millimeter' => function ($value) {
        return $value * 1e+10;
      },
      'square_micrometer' => function ($value) {
        return $value * 1e+16;
      },
      'hectare' => function ($value) {
        return $value;
      },
      'square_mile' => function ($value) {
        return $value / 259;
      },
      'square_yard' => function ($value) {
        return $value * 11960;
      },
      'square_foot' => function ($value) {
        return $value * 107639;
      },
      'square_inch' => function ($value) {
        return $value * 15500031;
      },
      'acre' => function ($value) {
        return $value * 2.47105;
      }
    ];

    if (isset($conversionTable[$toUnit])) {
      $conversionFunction = $conversionTable[$toUnit];
      $result = $conversionFunction($value);
    } else {
      $result = null;
    }

    return $result;
  }

  public function convertFromSquareMile($value, $toUnit)
  {
    $conversionTable = [
      'square_meter' => function ($value) {
        return $value * 2.59e+6;
      },
      'square_kilometer' => function ($value) {
        return $value * 2.59;
      },
      'square_centimeter' => function ($value) {
        return $value * 2.59e+10;
      },
      'square_millimeter' => function ($value) {
        return $value * 2.59e+12;
      },
      'square_micrometer' => function ($value) {
        return $value * 2.59e+18;
      },
      'hectare' => function ($value) {
        return $value * 259;
      },
      'square_mile' => function ($value) {
        return $value;
      },
      'square_yard' => function ($value) {
        return $value * 3.098e+6;
      },
      'square_foot' => function ($value) {
        return $value * 2.788e+7;
      },
      'square_inch' => function ($value) {
        return $value * 4.014e+9;
      },
      'acre' => function ($value) {
        return $value * 640;
      }
    ];

    if (isset($conversionTable[$toUnit])) {
      $conversionFunction = $conversionTable[$toUnit];
      $result = $conversionFunction($value);
    } else {
      $result = null;
    }

    return $result;
  }

  public function convertFromSquareYard($value, $toUnit)
  {
    $conversionTable = [
      'square_meter' => function ($value) {
        return $value / 1.196;
      },
      'square_kilometer' => function ($value) {
        return $value / 1.196e+12;
      },
      'square_centimeter' => function ($value) {
        return $value * 8361;
      },
      'square_millimeter' => function ($value) {
        return $value * 836127;
      },
      'square_micrometer' => function ($value) {
        return $value * 8.361e+11;
      },
      'hectare' => function ($value) {
        return $value / 1.196e+10;
      },
      'square_mile' => function ($value) {
        return $value / 3.098e+9;
      },
      'square_yard' => function ($value) {
        return $value;
      },
      'square_foot' => function ($value) {
        return $value * 9;
      },
      'square_inch' => function ($value) {
        return $value * 1296;
      },
      'acre' => function ($value) {
        return $value / 4840;
      }
    ];

    if (isset($conversionTable[$toUnit])) {
      $conversionFunction = $conversionTable[$toUnit];
      $result = $conversionFunction($value);
    } else {
      $result = null;
    }

    return $result;
  }

  public function convertFromSquareFoot($value, $toUnit)
  {
    $conversionTable = [
      'square_meter' => function ($value) {
        return $value / 10.764;
      },
      'square_kilometer' => function ($value) {
        return $value / 1.076e+7;
      },
      'square_centimeter' => function ($value) {
        return $value * 929.03;
      },
      'square_millimeter' => function ($value) {
        return $value * 92903;
      },
      'square_micrometer' => function ($value) {
        return $value * 9.29e+10;
      },
      'hectare' => function ($value) {
        return $value / 1.076e+6;
      },
      'square_mile' => function ($value) {
        return $value / 2.788e+7;
      },
      'square_yard' => function ($value) {
        return $value / 9;
      },
      'square_foot' => function ($value) {
        return $value;
      },
      'square_inch' => function ($value) {
        return $value * 144;
      },
      'acre' => function ($value) {
        return $value / 43560;
      }
    ];

    if (isset($conversionTable[$toUnit])) {
      $conversionFunction = $conversionTable[$toUnit];
      $result = $conversionFunction($value);
    } else {
      $result = null;
    }

    return $result;
  }

  public function convertFromSquareInch($value, $toUnit)
  {
    $conversionTable = [
      'square_meter' => function ($value) {
        return $value / 1550;
      },
      'square_kilometer' => function ($value) {
        return $value / 1.55e+9;
      },
      'square_centimeter' => function ($value) {
        return $value * 6.452;
      },
      'square_millimeter' => function ($value) {
        return $value * 645.16;
      },
      'square_micrometer' => function ($value) {
        return $value * 6.452e+8;
      },
      'hectare' => function ($value) {
        return $value / 1.55e+7;
      },
      'square_mile' => function ($value) {
        return $value / 4.014e+9;
      },
      'square_yard' => function ($value) {
        return $value / 1296;
      },
      'square_foot' => function ($value) {
        return $value / 144;
      },
      'square_inch' => function ($value) {
        return $value;
      },
      'acre' => function ($value) {
        return $value / 6.273e+6;
      }
    ];

    if (isset($conversionTable[$toUnit])) {
      $conversionFunction = $conversionTable[$toUnit];
      $result = $conversionFunction($value);
    } else {
      $result = null;
    }

    return $result;
  }

  public function convertFromAcre($value, $toUnit)
  {
    $conversionTable = [
      'square_meter' => function ($value) {
        return $value * 4046.86;
      },
      'square_kilometer' => function ($value) {
        return $value * 0.00404686;
      },
      'square_centimeter' => function ($value) {
        return $value * 40468564.224;
      },
      'square_millimeter' => function ($value) {
        return $value * 4046856422.4;
      },
      'square_micrometer' => function ($value) {
        return $value * 4.0468564224e+12;
      },
      'hectare' => function ($value) {
        return $value * 0.404686;
      },
      'square_mile' => function ($value) {
        return $value * 0.0015625;
      },
      'square_yard' => function ($value) {
        return $value * 4840;
      },
      'square_foot' => function ($value) {
        return $value * 43560;
      },
      'square_inch' => function ($value) {
        return $value * 6272640;
      },
      'acre' => function ($value) {
        return $value;
      }
    ];

    if (isset($conversionTable[$toUnit])) {
      $conversionFunction = $conversionTable[$toUnit];
      $result = $conversionFunction($value);
    } else {
      $result = null;
    }

    return $result;
  }
}

class VolumeConverter extends UnitConversion
{
  public function convert($value, $fromUnit, $toUnit)
  {
    if ($fromUnit === 'cubic_meter') {
      $result = $this->convertFromCubicMeter($value, $toUnit);
    } elseif ($fromUnit === 'liter') {
      $result = $this->convertFromLiter($value, $toUnit);
    } elseif ($fromUnit === 'mililiter') {
      $result = $this->convertFromMililiter($value, $toUnit);
    } elseif ($fromUnit === 'cubic_mile') {
      $result = $this->convertFromCubicMile($value, $toUnit);
    } elseif ($fromUnit === 'cubic_yard') {
      $result = $this->convertFromCubicYard($value, $toUnit);
    } elseif ($fromUnit === 'cubic_foot') {
      $result = $this->convertFromCubicFoot($value, $toUnit);
    } elseif ($fromUnit === 'cubic_inch') {
      $result = $this->convertFromCubicInch($value, $toUnit);
    } else {
      $result = null;
    }

    return $result;
  }

  public function convertFromCubicMeter($value, $toUnit)
  {
    $conversionTable = [
      'cubic_meter' => function ($value) {
        return $value;
      },
      'liter' => function ($value) {
        return $value * 1000;
      },
      'milliliter' => function ($value) {
        return $value * 1000000;
      },
      'cubic_mile' => function ($value) {
        return $value * 0.00000000023991;
      },
      'cubic_yard' => function ($value) {
        return $value * 1.30795062;
      },
      'cubic_foot' => function ($value) {
        return $value * 35.3146667;
      },
      'cubic_inch' => function ($value) {
        return $value * 61023.7441;
      }
    ];

    if (isset($conversionTable[$toUnit])) {
      $conversionFunction = $conversionTable[$toUnit];
      $result = $conversionFunction($value);
    } else {
      return null;
    }

    return $result;
  }

  public function convertFromLiter($value, $toUnit)
  {
    $conversionTable = [
      'cubic_meter' => function ($value) {
        return $value / 1000;
      },
      'liter' => function ($value) {
        return $value;
      },
      'milliliter' => function ($value) {
        return $value * 1000;
      },
      'cubic_mile' => function ($value) {
        return $value * 0.00000000023991;
      },
      'cubic_yard' => function ($value) {
        return $value * 0.00130795062;
      },
      'cubic_foot' => function ($value) {
        return $value * 0.0353146667;
      },
      'cubic_inch' => function ($value) {
        return $value * 61.0237441;
      }
    ];

    if (isset($conversionTable[$toUnit])) {
      $conversionFunction = $conversionTable[$toUnit];
      $result = $conversionFunction($value);
    } else {
      return null;
    }

    return $result;
  }

  public function convertFromMililiter($value, $toUnit)
  {
    $conversionTable = [
      'cubic_meter' => function ($value) {
        return $value / 1000000;
      },
      'liter' => function ($value) {
        return $value / 1000;
      },
      'mililiter' => function ($value) {
        return $value;
      },
      'cubic_mile' => function ($value) {
        return $value * 0.00000000000000023991;
      },
      'cubic_yard' => function ($value) {
        return $value * 0.00000130795062;
      },
      'cubic_foot' => function ($value) {
        return $value * 0.0000353146667;
      },
      'cubic_inch' => function ($value) {
        return $value * 0.0610237441;
      }
    ];

    if (isset($conversionTable[$toUnit])) {
      $conversionFunction = $conversionTable[$toUnit];
      $result = $conversionFunction($value);
    } else {
      return null;
    }

    return $result;
  }

  public function convertFromCubicMile($value, $toUnit)
  {
    $conversionTable = [
      'cubic_meter' => function ($value) {
        return $value * 4168181825.4;
      },
      'liter' => function ($value) {
        return $value * 4.1681818254e+12;
      },
      'milliliter' => function ($value) {
        return $value * 4.1681818254e+15;
      },
      'cubic_mile' => function ($value) {
        return $value;
      },
      'cubic_yard' => function ($value) {
        return $value * 5451776000;
      },
      'cubic_foot' => function ($value) {
        return $value * 147197952000;
      },
      'cubic_inch' => function ($value) {
        return $value * 2.143347118e+14;
      }
    ];

    if (isset($conversionTable[$toUnit])) {
      $conversionFunction = $conversionTable[$toUnit];
      $result = $conversionFunction($value);
    } else {
      return null;
    }

    return $result;
  }

  public function convertFromCubicYard($value, $toUnit)
  {
    $conversionTable = [
      'cubic_meter' => function ($value) {
        return $value / 1000;
      },
      'liter',
      'mililiter',
      'cubic_mile',
      'cubic_yard',
      'cubic_foot',
      'cubic_inch'
    ];

    if (isset($conversionTable[$toUnit])) {
      $conversionFunction = $conversionTable[$toUnit];
      $result = $conversionFunction($value);
    } else {
      return null;
    }

    return $result;
  }

  public function convertFromCubicFoot($value, $toUnit)
  {
    $conversionTable = [
      'cubic_meter' => function ($value) {
        return $value / 35.3146667;
      },
      'liter' => function ($value) {
        return $value * 28.3168466;
      },
      'milliliter' => function ($value) {
        return $value * 28316.8466;
      },
      'cubic_mile' => function ($value) {
        return $value * 0.00000000020712373;
      },
      'cubic_yard' => function ($value) {
        return $value * 0.037037037;
      },
      'cubic_foot' => function ($value) {
        return $value;
      },
      'cubic_inch' => function ($value) {
        return $value * 1728;
      }
    ];

    if (isset($conversionTable[$toUnit])) {
      $conversionFunction = $conversionTable[$toUnit];
      $result = $conversionFunction($value);
    } else {
      return null;
    }

    return $result;
  }

  public function convertFromCubicInch($value, $toUnit)
  {
    $conversionTable = [
      'cubic_meter' => function ($value) {
        return $value / 61023.7441;
      },
      'liter' => function ($value) {
        return $value / 61.0237441;
      },
      'milliliter' => function ($value) {
        return $value * 16.387064;
      },
      'cubic_mile' => function ($value) {
        return $value * 3.93146568e-13;
      },
      'cubic_yard' => function ($value) {
        return $value * 0.0000214334712;
      },
      'cubic_foot' => function ($value) {
        return $value * 0.000578703704;
      },
      'cubic_inch' => function ($value) {
        return $value;
      }
    ];

    if (isset($conversionTable[$toUnit])) {
      $conversionFunction = $conversionTable[$toUnit];
      $result = $conversionFunction($value);
    } else {
      return null;
    }

    return $result;
  }
}

class WeightConverter extends UnitConversion
{
  public function convert($value, $fromUnit, $toUnit)
  {
    if ($fromUnit === 'kilogram') {
      $result = $this->convertFromKilogram($value, $toUnit);
    } elseif ($fromUnit === 'gram') {
      $result = $this->convertFromGram($value, $toUnit);
    } elseif ($fromUnit === 'miligram') {
      $result = $this->convertFromMilligram($value, $toUnit);
    } elseif ($fromUnit === 'ton') {
      $result = $this->convertFromTon($value, $toUnit);
    } elseif ($fromUnit === 'pound') {
      $result = $this->$this->convertFromPound($value, $toUnit);
    } elseif ($fromUnit === 'ounce') {
      $result = $this->convertFromOunce($value, $toUnit);
    } elseif ($fromUnit === 'carrat') {
      $result = $this->convertFromCarrat($value, $toUnit);
    } elseif ($fromUnit === 'atomic_mass_unit') {
      $result = $this->convertFromAtomicMassUnit($value, $toUnit);
    } else {
      $result = null;
    }

    return $result;
  }

  public function convertFromKilogram($value, $toUnit)
  {
    $conversionTable = [
      'kilogram' => function ($value) {
        return $value;
      },
      'gram' => function ($value) {
        return $value * 1000;
      },
      'milligram' => function ($value) {
        return $value * 1000000;
      },
      'ton' => function ($value) {
        return $value / 1000;
      },
      'pound' => function ($value) {
        return $value * 2.20462;
      },
      'ounce' => function ($value) {
        return $value * 35.27396;
      },
      'carat' => function ($value) {
        return $value * 5000;
      },
      'atomic_mass_unit' => function ($value) {
        return $value * 6.02214076e+26;
      }
    ];

    if (isset($conversionTable[$toUnit])) {
      $conversionFunction = $conversionTable[$toUnit];
      $result = $conversionFunction($value);
    } else {
      return null;
    }

    return $result;
  }

  public function convertFromGram($value, $toUnit)
  {
    $conversionTable = [
      'kilogram' => function ($value) {
        return $value / 1000;
      },
      'gram' => function ($value) {
        return $value;
      },
      'milligram' => function ($value) {
        return $value * 1000;
      },
      'ton' => function ($value) {
        return $value / 1000000;
      },
      'pound' => function ($value) {
        return $value * 0.00220462;
      },
      'ounce' => function ($value) {
        return $value * 0.03527396;
      },
      'carat' => function ($value) {
        return $value * 5;
      },
      'atomic_mass_unit' => function ($value) {
        return $value * 6.02214076e+23;
      }
    ];

    if (isset($conversionTable[$toUnit])) {
      $conversionFunction = $conversionTable[$toUnit];
      $result = $conversionFunction($value);
    } else {
      return null;
    }

    return $result;
  }

  public function convertFromMilligram($value, $toUnit)
  {
    $conversionTable = [
      'kilogram' => function ($value) {
        return $value / 1000000;
      },
      'gram' => function ($value) {
        return $value / 1000;
      },
      'miligram' => function ($value) {
        return $value;
      },
      'ton' => function ($value) {
        return $value / 1e+9;
      },
      'pound' => function ($value) {
        return $value * 2.20462e-6;
      },
      'ounce' => function ($value) {
        return $value * 3.527396e-5;
      },
      'carat' => function ($value) {
        return $value * 5e-3;
      },
      'atomic_mass_unit' => function ($value) {
        return $value * 6.02214076e+20;
      }
    ];

    if (isset($conversionTable[$toUnit])) {
      $conversionFunction = $conversionTable[$toUnit];
      $result = $conversionFunction($value);
    } else {
      return null;
    }

    return $result;
  }

  public function convertFromTon($value, $toUnit)
  {
    $conversionTable = [
      'kilogram' => function ($value) {
        return $value * 1000;
      },
      'gram' => function ($value) {
        return $value * 1e+6;
      },
      'miligram' => function ($value) {
        return $value * 1e+9;
      },
      'ton' => function ($value) {
        return $value;
      },
      'pound' => function ($value) {
        return $value * 2204.62;
      },
      'ounce' => function ($value) {
        return $value * 35273.96;
      },
      'carat' => function ($value) {
        return $value * 5e+6;
      },
      'atomic_mass_unit' => function ($value) {
        return $value * 6.02214076e+29;
      }
    ];

    if (isset($conversionTable[$toUnit])) {
      $conversionFunction = $conversionTable[$toUnit];
      $result = $conversionFunction($value);
    } else {
      return null;
    }

    return $result;
  }

  public function convertFromPound($value, $toUnit)
  {
    $conversionTable = [
      'kilogram' => function ($value) {
        return $value * 0.453592;
      },
      'gram' => function ($value) {
        return $value * 453.592;
      },
      'miligram' => function ($value) {
        return $value * 453592;
      },
      'ton' => function ($value) {
        return $value * 0.000453592;
      },
      'pound' => function ($value) {
        return $value;
      },
      'ounce' => function ($value) {
        return $value * 16;
      },
      'carat' => function ($value) {
        return $value * 2267.96;
      },
      'atomic_mass_unit' => function ($value) {
        return $value * 2.73159734e+26;
      }
    ];

    if (isset($conversionTable[$toUnit])) {
      $conversionFunction = $conversionTable[$toUnit];
      $result = $conversionFunction($value);
    } else {
      return null;
    }

    return $result;
  }

  public function convertFromOunce($value, $toUnit)
  {
    $conversionTable = [
      'kilogram' => function ($value) {
        return $value * 0.0283495;
      },
      'gram' => function ($value) {
        return $value * 28.3495;
      },
      'miligram' => function ($value) {
        return $value * 28349.5;
      },
      'ton' => function ($value) {
        return $value * 2.835e-5;
      },
      'pound' => function ($value) {
        return $value * 0.0625;
      },
      'ounce' => function ($value) {
        return $value;
      },
      'carat' => function ($value) {
        return $value * 141.748;
      },
      'atomic_mass_unit' => function ($value) {
        return $value * 1.70724538e+25;
      }
    ];

    if (isset($conversionTable[$toUnit])) {
      $conversionFunction = $conversionTable[$toUnit];
      $result = $conversionFunction($value);
    } else {
      return null;
    }

    return $result;
  }

  public function convertFromCarrat($value, $toUnit)
  {
    $conversionTable = [
      'kilogram' => function ($value) {
        return $value * 0.0002;
      },
      'gram' => function ($value) {
        return $value * 0.2;
      },
      'miligram' => function ($value) {
        return $value * 200;
      },
      'ton' => function ($value) {
        return $value * 2e-7;
      },
      'pound' => function ($value) {
        return $value * 0.000440925;
      },
      'ounce' => function ($value) {
        return $value * 0.00705479;
      },
      'carrat' => function ($value) {
        return $value;
      },
      'atomic_mass_unit' => function ($value) {
        return $value * 1.20442732e+22;
      }
    ];

    if (isset($conversionTable[$toUnit])) {
      $conversionFunction = $conversionTable[$toUnit];
      $result = $conversionFunction($value);
    } else {
      return null;
    }

    return $result;
  }

  public function convertFromAtomicMassUnit($value, $toUnit)
  {
    $conversionTable = [
      'kilogram' => function ($value) {
        return $value * 1.66053904e-27;
      },
      'gram' => function ($value) {
        return $value * 1.66053904e-24;
      },
      'miligram' => function ($value) {
        return $value * 1.66053904e-21;
      },
      'ton' => function ($value) {
        return $value * 1.66053904e-30;
      },
      'pound' => function ($value) {
        return $value * 3.66086148e-24;
      },
      'ounce' => function ($value) {
        return $value * 5.85737837e-23;
      },
      'carrat' => function ($value) {
        return $value * 8.27180613e-25;
      },
      'atomic_mass_unit' => function ($value) {
        return $value;
      }
    ];

    if (isset($conversionTable[$toUnit])) {
      $conversionFunction = $conversionTable[$toUnit];
      $result = $conversionFunction($value);
    } else {
      return null;
    }

    return $result;
  }
}
