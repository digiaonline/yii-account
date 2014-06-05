<?php
/**
 * TokenGenerator class file.
 * @author Christoffer Niska <christoffer.niska@nordsoftware.com>
 * @copyright Copyright &copy; Nord Software Ltd 2014-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package nordsoftware.yii_account.components
 */

namespace nordsoftware\yii_account\components;

class TokenGenerator extends \CApplicationComponent
{
    const DEFAULT_TOKEN_LENGTH = 32;

    /**
     * @var int token length.
     */
    public $length = self::DEFAULT_TOKEN_LENGTH;

    /**
     * @var int token strength.
     */
    public $strength = \SecurityLib\Strength::MEDIUM;

    /**
     * @var string characters to use when generating a token string.
     */
    public $chars = 'abcdefghijklmnopqrstuvxyz0123456789';

    /**
     * @var \RandomLib\Factory RandomLib factory instance.
     */
    private $_factory;

    /**
     * Generates a new random token.
     *
     * @return string the token
     */
    public function generate()
    {
        $generator = $this->getFactory()->getGenerator(new \SecurityLib\Strength($this->strength));

        return $generator->generateString($this->length, $this->chars);
    }

    /**
     * Getter for the RandomLib factory instance.
     *
     * @return \RandomLib\Factory
     */
    protected function getFactory()
    {
        if (!isset($this->_factory)) {
            $this->_factory = new \RandomLib\Factory();
        }

        return $this->_factory;
    }
}