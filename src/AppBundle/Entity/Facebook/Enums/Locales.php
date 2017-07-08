<?php
/**
 * INSERT INTO locales (id, value) VALUES (1, 'n_US'), (2, 'a_ES'), (3, 's_CZ'), (4, 'x_PH'), (5, 'y_GB'), (6, 'a_DK'), (7, 'e_DE'), (8, 'u_ES'), (9, 'n_UD'), (10, 'es_LA'), (11, 'es_ES'), (12, 'gn_PY'), (13, 'fi_FI'), (14, 'fr_FR'), (15, 'gl_ES'), (16, 'hu_HU'), (17, 'it_IT'), (18, 'ja_JP'), (19, 'ko_KR'), (20, 'nb_NO'), (21, 'nn_NO'), (22, 'nl_NL'), (23, 'fy_NL'), (24, 'pl_PL'), (25, 'pt_BR'), (26, 'pt_PT'), (27, 'ro_RO'), (28, 'ru_RU'), (29, 'sk_SK'), (30, 'sl_SI'), (31, 'sv_SE'), (32, 'th_TH'), (33, 'tr_TR'), (34, 'ku_TR'), (35, 'zh_CN'), (36, 'zh_HK'), (37, 'zh_TW'), (38, 'af_ZA'), (39, 'sq_AL'), (40, 'hy_AM'), (41, 'az_AZ'), (42, 'be_BY'), (43, 'bn_IN'), (44, 'bs_BA'), (45, 'bg_BG'), (46, 'hr_HR'), (47, 'nl_BE'), (48, 'en_GB'), (49, 'et_EE'), (50, 'fo_FO'), (51, 'fr_CA'), (52, 'ka_GE'), (53, 'el_GR'), (54, 'gu_IN'), (55, 'hi_IN'), (56, 'is_IS'), (57, 'id_ID'), (58, 'ga_IE'), (59, 'jv_ID'), (60, 'kn_IN'), (61, 'kk_KZ'), (62, 'lv_LV'), (63, 'lt_LT'), (64, 'mk_MK'), (65, 'mg_MG'), (66, 'ms_MY'), (67, 'mt_MT'), (68, 'mr_IN'), (69, 'mn_MN'), (70, 'ne_NP'), (71, 'pa_IN'), (72, 'sr_RS'), (73, 'so_SO'), (74, 'sw_KE'), (75, 'tl_PH'), (76, 'ta_IN'), (77, 'te_IN'), (78, 'ml_IN'), (79, 'uk_UA'), (80, 'uz_UZ'), (81, 'vi_VN'), (82, 'km_KH'), (83, 'tg_TJ'), (84, 'ar_AR'), (85, 'he_IL'), (86, 'ur_PK'), (87, 'fa_IR'), (88, 'ps_AF'), (89, 'my_MM'), (90, 'qz_MM'), (91, 'or_IN'), (92, 'si_LK'), (93, 'rw_RW'), (94, 'cb_IQ'), (95, 'ha_NG'), (96, 'ja_KS'), (97, 'br_FR'), (98, 'tz_MA'), (99, 'co_FR'), (100, 'as_IN'), (101, 'ff_NG'), (102, 'sc_IT'), (103, 'sz_PL');
 */

namespace AppBundle\Entity\Facebook\Enums;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Locales
 *
 * @author Antoine Cusset <a.cusset@gmail.com>
 * @link https://github.com/acusset
 * @category
 * @license
 * @package AppBundle\Entity\Facebook\Enums
 *
 * @ORM\Table(name="locales")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LocalesRepository")
 */
class Locales
{

    /**
     * Facebook supported locals
     */
    const en_US = 'en_US';
    const ca_ES = 'ca_ES';
    const cs_CZ = 'cs_CZ';
    const cx_PH = 'cx_PH';
    const cy_GB = 'cy_GB';
    const da_DK = 'da_DK';
    const de_DE = 'de_DE';
    const eu_ES = 'eu_ES';
    const en_UD = 'en_UD';
    const es_LA = 'es_LA';
    const es_ES = 'es_ES';
    const gn_PY = 'gn_PY';
    const fi_FI = 'fi_FI';
    const fr_FR = 'fr_FR';
    const gl_ES = 'gl_ES';
    const hu_HU = 'hu_HU';
    const it_IT = 'it_IT';
    const ja_JP = 'ja_JP';
    const ko_KR = 'ko_KR';
    const nb_NO = 'nb_NO';
    const nn_NO = 'nn_NO';
    const nl_NL = 'nl_NL';
    const fy_NL = 'fy_NL';
    const pl_PL = 'pl_PL';
    const pt_BR = 'pt_BR';
    const pt_PT = 'pt_PT';
    const ro_RO = 'ro_RO';
    const ru_RU = 'ru_RU';
    const sk_SK = 'sk_SK';
    const sl_SI = 'sl_SI';
    const sv_SE = 'sv_SE';
    const th_TH = 'th_TH';
    const tr_TR = 'tr_TR';
    const ku_TR = 'ku_TR';
    const zh_CN = 'zh_CN';
    const zh_HK = 'zh_HK';
    const zh_TW = 'zh_TW';
    const af_ZA = 'af_ZA';
    const sq_AL = 'sq_AL';
    const hy_AM = 'hy_AM';
    const az_AZ = 'az_AZ';
    const be_BY = 'be_BY';
    const bn_IN = 'bn_IN';
    const bs_BA = 'bs_BA';
    const bg_BG = 'bg_BG';
    const hr_HR = 'hr_HR';
    const nl_BE = 'nl_BE';
    const en_GB = 'en_GB';
    const et_EE = 'et_EE';
    const fo_FO = 'fo_FO';
    const fr_CA = 'fr_CA';
    const ka_GE = 'ka_GE';
    const el_GR = 'el_GR';
    const gu_IN = 'gu_IN';
    const hi_IN = 'hi_IN';
    const is_IS = 'is_IS';
    const id_ID = 'id_ID';
    const ga_IE = 'ga_IE';
    const jv_ID = 'jv_ID';
    const kn_IN = 'kn_IN';
    const kk_KZ = 'kk_KZ';
    const lv_LV = 'lv_LV';
    const lt_LT = 'lt_LT';
    const mk_MK = 'mk_MK';
    const mg_MG = 'mg_MG';
    const ms_MY = 'ms_MY';
    const mt_MT = 'mt_MT';
    const mr_IN = 'mr_IN';
    const mn_MN = 'mn_MN';
    const ne_NP = 'ne_NP';
    const pa_IN = 'pa_IN';
    const sr_RS = 'sr_RS';
    const so_SO = 'so_SO';
    const sw_KE = 'sw_KE';
    const tl_PH = 'tl_PH';
    const ta_IN = 'ta_IN';
    const te_IN = 'te_IN';
    const ml_IN = 'ml_IN';
    const uk_UA = 'uk_UA';
    const uz_UZ = 'uz_UZ';
    const vi_VN = 'vi_VN';
    const km_KH = 'km_KH';
    const tg_TJ = 'tg_TJ';
    const ar_AR = 'ar_AR';
    const he_IL = 'he_IL';
    const ur_PK = 'ur_PK';
    const fa_IR = 'fa_IR';
    const ps_AF = 'ps_AF';
    const my_MM = 'my_MM';
    const qz_MM = 'qz_MM';
    const or_IN = 'or_IN';
    const si_LK = 'si_LK';
    const rw_RW = 'rw_RW';
    const cb_IQ = 'cb_IQ';
    const ha_NG = 'ha_NG';
    const ja_KS = 'ja_KS';
    const br_FR = 'br_FR';
    const tz_MA = 'tz_MA';
    const co_FR = 'co_FR';
    const as_IN = 'as_IN';
    const ff_NG = 'ff_NG';
    const sc_IT = 'sc_IT';
    const sz_PL = 'sz_PL';

    const SUPPORTED_LOCALES = [
        self::en_US,
        self::ca_ES,
        self::cs_CZ,
        self::cx_PH,
        self::cy_GB,
        self::da_DK,
        self::de_DE,
        self::eu_ES,
        self::en_UD,
        self::es_LA,
        self::es_ES,
        self::gn_PY,
        self::fi_FI,
        self::fr_FR,
        self::gl_ES,
        self::hu_HU,
        self::it_IT,
        self::ja_JP,
        self::ko_KR,
        self::nb_NO,
        self::nn_NO,
        self::nl_NL,
        self::fy_NL,
        self::pl_PL,
        self::pt_BR,
        self::pt_PT,
        self::ro_RO,
        self::ru_RU,
        self::sk_SK,
        self::sl_SI,
        self::sv_SE,
        self::th_TH,
        self::tr_TR,
        self::ku_TR,
        self::zh_CN,
        self::zh_HK,
        self::zh_TW,
        self::af_ZA,
        self::sq_AL,
        self::hy_AM,
        self::az_AZ,
        self::be_BY,
        self::bn_IN,
        self::bs_BA,
        self::bg_BG,
        self::hr_HR,
        self::nl_BE,
        self::en_GB,
        self::et_EE,
        self::fo_FO,
        self::fr_CA,
        self::ka_GE,
        self::el_GR,
        self::gu_IN,
        self::hi_IN,
        self::is_IS,
        self::id_ID,
        self::ga_IE,
        self::jv_ID,
        self::kn_IN,
        self::kk_KZ,
        self::lv_LV,
        self::lt_LT,
        self::mk_MK,
        self::mg_MG,
        self::ms_MY,
        self::mt_MT,
        self::mr_IN,
        self::mn_MN,
        self::ne_NP,
        self::pa_IN,
        self::sr_RS,
        self::so_SO,
        self::sw_KE,
        self::tl_PH,
        self::ta_IN,
        self::te_IN,
        self::ml_IN,
        self::uk_UA,
        self::uz_UZ,
        self::vi_VN,
        self::km_KH,
        self::tg_TJ,
        self::ar_AR,
        self::he_IL,
        self::ur_PK,
        self::fa_IR,
        self::ps_AF,
        self::my_MM,
        self::qz_MM,
        self::or_IN,
        self::si_LK,
        self::rw_RW,
        self::cb_IQ,
        self::ha_NG,
        self::ja_KS,
        self::br_FR,
        self::tz_MA,
        self::co_FR,
        self::as_IN,
        self::ff_NG,
        self::sc_IT,
        self::sz_PL
    ];

    /**
     * @var int $id
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $value
     * @ORM\Column(type="string", length=10)
     */
    private $value;

    /**
     * Locales constructor.
     * @param $value
     */
    public function __construct($value)
    {
        if (in_array($value, self::SUPPORTED_LOCALES)) {
            $this->value = $value;
        } else {
            $this->value = 'default';
        }
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    function __toString()
    {
        return $this->value;
    }


}