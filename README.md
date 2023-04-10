Converter is a module to convert one currency to another.
Currency rates are taken from this site https://apilayer.com/marketplace/fixer-api#pricing
To import you should get api key and pass it to module configure (/admin/converter/config).
You also can select currencies there.
To convert currency you can use this service:
$res = \Drupal::service('converter.service_converter')->convert($value, $from, $to);
Where $value is value, $from is currency wich you want to convert and $to - is currency which you want convert to.
For example:
$res = \Drupal::service('converter.service_converter')->convert(250, 'USD', 'EUR');
