<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use EasyWeChat\Factory;
use App\DBAL\Types\SexType;
use App\Entity\{Consumer, Wechat};


class WechatMpapp extends Controller
{
    public function __construct(array $config)
    {
        $this->miniApp = Factory::miniProgram($config);
    }

    /**
     * @Route(
     *     name="mpapp_login_or_create",
     *     path="/mpapp/login_or_create",
     *     methods={"POST"},
     *     defaults={
     *         "_api_receive"= false,
     *         "_api_resource_class"=Consumer::class,
     *         "_api_collection_operation_name"="loginOrCreate"
     *     }
     * )
     */
    public function loginOrCreateAction(Request $request): Consumer
    {
        $code = $request->query->get('code');

        // $sessionInfos = $this->miniApp->auth->session($code);

        $openId = '1';

        $em = $this->getDoctrine()->getManager();

        $wechat = $em->getRepository(Wechat::class)
                ->findOneBy(['openId' => $openId]);

        if (!$wechat) {
            $now = new \DateTime("now");

            $consumer = (new Consumer())
                      ->setImage('')
                      ->setNickName('')
                      ->setSex(SexType::MAN)
                      ->setFirstLoginAt($now);
                  // ->setOpenId($openId);

            $wechat = (new Wechat())
                ->setOpenId($openId)
                ->setConsumer($consumer);

            $em->persist($wechat);

            $em->flush();

            return $consumer;
        }

        $consumer = $wechat->getConsumer();

        $consumer->setLastLoginAt(new \DateTime("now"));

        return $consumer;
    }
}
