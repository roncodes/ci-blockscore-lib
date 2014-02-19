BlockScore Codeigniter Library
=============

[BlockScore](http://blockscore.com) provides a fast and accurate identity verification API. Implement knowledge-based authentication and verification in minutes and start verifying your customers.

The documentation of the API can be found at https://manage.blockscore.com/docs/index

## Install ##

To install the BlockScore CodeIgniter Library, you need [composer](http://getcomposer.org) first:

``` console
curl -sS https://getcomposer.org/installer | php
```

Edit `composer.json`:

```json
{
    "require": {
        "blockscore/blockscore": "1.*"
    }
}
```

Install the depencies by executing `composer`:

```console
php composer.phar install
```

Usage
-------------

<h4>Get verification on identity:</h4>
<pre>
$this->load->library('BlockScore');
$person = array(
    'type' => 'us_citizen', // or international_citizen
    'date_of_birth' => '1990-12-28',
    'name' => array(
        'first' => 'John',
        'middle' => 'Don',
        'last' => 'Doe'
    ),
    'address' => array(
        'street1' => '123 Abc St.',
        'street2' => 'Apt #123',
        'city' => 'Seattle',
        'state' => 'WA',
        'postal_code' => '98116',
        'country' => 'US'
    ),
    'identification' => array(
        'ssn' => '0000' // or 'passport' => 'XXXXXXX', 'gender' => 'male'
    )
);
$this->blockscore->verification($person);
</pre>   

<h4>Response:</h4>
<pre>
array (size=10)
  'id' => string '530539413066370002560000' (length=24)
  'created_at' => int 1392851265
  'updated_at' => int 1392851265
  'type' => string 'us_citizen' (length=10)
  'status' => string 'valid' (length=5)
  'livemode' => boolean false
  'date_of_birth' => string '1990-12-28' (length=10)
  'identification' => 
    array (size=1)
      'ssn' => string '0000' (length=4)
  'name' => 
    array (size=3)
      'first' => string 'John' (length=4)
      'middle' => string 'Don' (length=3)
      'last' => string 'Doe' (length=3)
  'address' => 
    array (size=6)
      'street1' => string '123 Abc St.' (length=11)
      'street2' => string 'Apt #123' (length=8)
      'city' => string 'Seattle' (length=7)
      'state' => string 'WA' (length=2)
      'postal_code' => string '98116' (length=5)
      'country' => string 'US' (length=2) 
</pre>