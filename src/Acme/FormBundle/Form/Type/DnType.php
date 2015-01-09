<?php
namespace Acme\FormBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceList;

class DnType extends AbstractType{
	
	public function buildForm(FormBuilderInterface $builder, array $options){
		$builder
		->add('rootCaPassword', 'password', array('label'=>'Hasło głównego certyfikatu'))
		->add('caName', 'text', array('label'=>'Nazwa Certyfikatu'))
		->add('caPassword', 'repeated',  array(
				'type'=>'password',
				'invalid_message'=>'Podane hasła różnią się',
				'first_options'=>array('label'=>'Hasło zabezpieczające'),
				'second_options'=>array('label'=>'Powtórz hasło')
		))
		->add('countryName','choice',  array(
				'label'=>'Kraj',
				'choice_list'=> new ChoiceList(
					array('US', 'CA', 'AX', 'AD', 'AE', 'AF', 'AG', 'AI', 
							'AL', 'AM', 'AN', 'AO', 'AQ', 'AR', 'AS', 'AT', 
							'AU', 'AW', 'AZ', 'BA', 'BB', 'BD', 'BE', 'BF', 
							'BG', 'BH', 'BI', 'BJ', 'BM', 'BN', 'BO', 'BR', 
							'BS', 'BT', 'BV', 'BW', 'BZ', 'CA', 'CC', 'CF', 
							'CH', 'CI', 'CK', 'CL', 'CM', 'CN', 'CO', 'CR', 
							'CS', 'CV', 'CX', 'CY', 'CZ', 'DE', 'DJ', 'DK', 
							'DM', 'DO', 'DZ', 'EC', 'EE', 'EG', 'EH', 'ER', 
							'ES', 'ET', 'FI', 'FJ', 'FK', 'FM', 'FO', 'FR', 
							'FX', 'GA', 'GB', 'GD', 'GE', 'GF', 'GG', 'GH', 
							'GI', 'GL', 'GM', 'GN', 'GP', 'GQ', 'GR', 'GS', 
							'GT', 'GU', 'GW', 'GY', 'HK', 'HM', 'HN', 'HR', 
							'HT', 'HU', 'ID', 'IE', 'IL', 'IM', 'IN', 'IO', 
							'IS', 'IT', 'JE', 'JM', 'JO', 'JP', 'KE', 'KG', 
							'KH', 'KI', 'KM', 'KN', 'KR', 'KW', 'KY', 'KZ', 
							'LA', 'LC', 'LI', 'LK', 'LS', 'LT', 'LU', 'LV', 
							'LY', 'MA', 'MC', 'MD', 'ME', 'MG', 'MH', 'MK', 
							'ML', 'MM', 'MN', 'MO', 'MP', 'MQ', 'MR', 'MS', 
							'MT', 'MU', 'MV', 'MW', 'MX', 'MY', 'MZ', 'NA', 
							'NC', 'NE', 'NF', 'NG', 'NI', 'NL', 'NO', 'NP', 
							'NR', 'NT', 'NU', 'NZ', 'OM', 'PA', 'PE', 'PF', 
							'PG', 'PH', 'PK', 'PL', 'PM', 'PN', 'PR', 'PS', 
							'PT', 'PW', 'PY', 'QA', 'RE', 'RO', 'RS', 'RU', 
							'RW', 'SA', 'SB', 'SC', 'SE', 'SG', 'SH', 'SI', 
							'SJ', 'SK', 'SL', 'SM', 'SN', 'SR', 'ST', 'SU', 
							'SV', 'SZ', 'TC', 'TD', 'TF', 'TG', 'TH', 'TJ', 
							'TK', 'TM', 'TN', 'TO', 'TP', 'TR', 'TT', 'TV', 
							'TW', 'TZ', 'UA', 'UG', 'UM', 'US', 'UY', 'UZ', 
							'VA', 'VC', 'VE', 'VG', 'VI', 'VN', 'VU', 'WF', 
							'WS', 'YE', 'YT', 'ZA', 'ZM'),
						
					array('United States of America', 'Canada', 'Aland Islands', 'Andorra', 
							'United Arab Emirates', 'Afghanistan', 'Antigua and Barbuda', 'Anguilla', 
							'Albania', 'Armenia', 'Netherlands Antilles', 'Angola', 
							'Antarctica', 'Argentina', 'American Samoa', 'Austria', 
							'Australia', 'Aruba', 'Azerbaijan', 'Bosnia and Herzegovina', 
							'Barbados', 'Bangladesh', 'Belgium', 'Burkina Faso', 'Bulgaria', 
							'Bahrain', 'Burundi', 'Benin', 'Bermuda', 'Brunei Darussalam', 
							'Bolivia', 'Brazil', 'Bahamas', 'Bhutan', 'Bouvet Island', 
							'Botswana', 'Belize', 'Canada', 'Cocos (Keeling) Islands', 
							'Central African Republic', 'Switzerland', 'Cote D\'Ivoire (Ivory Coast)', 
							'Cook Islands', 'Chile', 'Cameroon', 'China', 'Colombia', 'Costa Rica', 
							'Czechoslovakia (former)', 'Cape Verde', 'Christmas Island', 'Cyprus', 
							'Czech Republic', 'Germany', 'Djibouti', 'Denmark', 'Dominica', 
							'Dominican Republic', 'Algeria', 'Ecuador', 'Estonia', 'Egypt', 
							'Western Sahara', 'Eritrea', 'Spain', 'Ethiopia', 'Finland', 'Fiji', 
							'Falkland Islands (Malvinas)', 'Micronesia', 'Faroe Islands', 'France', 
							'France, Metropolitan', 'Gabon', 'Great Britain (UK)', 'Grenada', 'Georgia', 
							'French Guiana', 'Guernsey', 'Ghana', 'Gibraltar', 'Greenland', 'Gambia', 
							'Guinea', 'Guadeloupe', 'Equatorial Guinea', 'Greece', 'S. Georgia and S. Sandwich Isls.', 
							'Guatemala', 'Guam', 'Guinea-Bissau', 'Guyana', 'Hong Kong', 'Heard and McDonald Islands', 
							'Honduras', 'Croatia (Hrvatska)', 'Haiti', 'Hungary', 'Indonesia', 'Ireland', 'Israel', 
							'Isle of Man', 'India', 'British Indian Ocean Territory', 'Iceland', 'Italy', 'Jersey', 
							'Jamaica', 'Jordan', 'Japan', 'Kenya', 'Kyrgyzstan', 'Cambodia', 'Kiribati', 'Comoros', 
							'Saint Kitts and Nevis', 'Korea (South)', 'Kuwait', 'Cayman Islands', 'Kazakhstan', 
							'Laos', 'Saint Lucia', 'Liechtenstein', 'Sri Lanka', 'Lesotho', 'Lithuania', 'Luxembourg', 
							'Latvia', 'Libya', 'Morocco', 'Monaco', 'Moldova', 'Montenegro', 'Madagascar', 
							'Marshall Islands', 'Macedonia', 'Mali', 'Myanmar', 'Mongolia', 'Macau', 
							'Northern Mariana Islands', 'Martinique', 'Mauritania', 'Montserrat', 'Malta', 
							'Mauritius', 'Maldives', 'Malawi', 'Mexico', 'Malaysia', 'Mozambique', 'Namibia', 
							'New Caledonia', 'Niger', 'Norfolk Island', 'Nigeria', 'Nicaragua', 'Netherlands', 
							'Norway', 'Nepal', 'Nauru', 'Neutral Zone', 'Niue', 'New Zealand (Aotearoa)', 'Oman', 
							'Panama', 'Peru', 'French Polynesia', 'Papua New Guinea', 'Philippines', 'Pakistan', 
							'Poland', 'St. Pierre and Miquelon', 'Pitcairn', 'Puerto Rico', 'Palestinian Territory', 
							'Portugal', 'Palau', 'Paraguay', 'Qatar', 'Reunion', 'Romania', 'Serbia', 
							'Russian Federation', 'Rwanda', 'Saudi Arabia', 'Solomon Islands', 'Seychelles', 
							'Sweden', 'Singapore', 'St. Helena', 'Slovenia', 'Svalbard and Jan Mayen Islands', 
							'Slovak Republic', 'Sierra Leone', 'San Marino', 'Senegal', 'Suriname', 
							'Sao Tome and Principe', 'USSR (former)', 'El Salvador', 'Swaziland', 
							'Turks and Caicos Islands', 'Chad', 'French Southern Territories', 'Togo', 
							'Thailand', 'Tajikistan', 'Tokelau', 'Turkmenistan', 'Tunisia', 'Tonga', 'East Timor', 
							'Turkey', 'Trinidad and Tobago', 'Tuvalu', 'Taiwan', 'Tanzania', 'Ukraine', 'Uganda', 
							'US Minor Outlying Islands', 'United States', 'Uruguay', 'Uzbekistan', 
							'Vatican City State (Holy See)', 'Saint Vincent and the Grenadines', 'Venezuela', 
							'Virgin Islands (British)', 'Virgin Islands (U.S.)', 'Viet Nam', 'Vanuatu', 
							'Wallis and Futuna Islands', 'Samoa', 'Yemen', 'Mayotte', 'South Africa', 'Zambia')
				)
		))
		->add('stateOrProvinceName','text',  array('label'=>'Województwo lub stan'))
		->add('localityName','text',  array('label'=>'Miasto'))
		->add('organisationName','text',  array('label'=>'Nazwa organizacji'))
		->add('organisationUnitName','text',  array('label'=>'Nazwa jednostki'))
		->add('commonName', 'text', array('label'=>'Domena'))
		->add('emailAddress', 'email', array('label'=>'Adres e-mail'))			
		->add('send', 'submit', array('attr'=>array('class'=>'btn btn-default submit'), 'label'=>'Utwórz Certyfikat'));
	}
	
	public function getName(){
		return 'Dn';
	}
	
	
	public function setDefaultOptions(OptionsResolverInterface $resolver){
		$resolver->setDefaults(array(
				'data_class'=>'Acme\CertBundle\Entity\Dn',
				));
	}
	

}