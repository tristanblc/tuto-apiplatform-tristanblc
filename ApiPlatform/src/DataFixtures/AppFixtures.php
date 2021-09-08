<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Pret;
use App\Entity\Adherent;
use App\Entity\User;
use App\Repository\LivreRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class AppFixtures extends Fixture
{
    private $manager;
    private $faker;
    private $repoLivre;
    private $passwordEncoder;
    public function __construct(UserPasswordEncoderInterface $passwordEncoder, LivreRepository $repoLivre)
    {
       $this->faker=Factory::create("fr_FR");
       $this->passwordEncoder = $passwordEncoder;
       $this->repoLivre = $repoLivre;
    }
    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;
        $this->loadAdherent();
        $this->loadPret();

        $manager->flush();
    }
    /**
     * création des adherents
     *
     * @return void
     */
    public function loadAdherent(){
        $genre = ['male','female'];
        $commune = [
            "78003", "78005", "78006", "78007", "78009", "78010", "78013", "78015", "78020", "78029",
            "78030", "78031", "78033", "78034", "78036", "78043", "78048", "78049", "78050", "78053", "78057",
            "78062", "78068", "78070", "78071", "78072", "78073", "78076", "78077", "78082", "78084", "78087",
            "78089", "78090", "78092", "78096", "78104", "78107", "78108", "78113", "78117", "78118"
        ];
        for($i=0;$i<25;$i++){
            $adherent = new Adherent();
            $adherent->setNom($this->faker->lastName())
                    ->setPrenom($this->faker->firstName($genre[mt_rand(0,1)]))
                    ->setAdresse($this->faker->streetAddress())
                    ->setTel($this->faker->phoneNumber())
                    ->setCodeCommune($commune[mt_rand(0,sizeof($commune)-1)])
                    ->setMail(strtolower($adherent->getNom())."@gmail.com")
                    ->setPassword(password_hash($adherent->getNom(),PASSWORD_BCRYPT));
            $this->addReference("adherent".$i,$adherent);
            $this->manager->persist($adherent);
        }
        $adherent = new Adherent();
            $adherent->setNom("Rolland")
                    ->setPrenom("Stephane")
                
                    ->setMail("admin@gmail.com")
                    ->setPassword(password_hash("Rolland",PASSWORD_BCRYPT));
        $this->manager->flush();

    }

       /**
     * création des prêts
     *
     * @return void
     */
    public function loadPret()
    {
 
        for ($i = 0; $i < 25; $i++) { // pour chaque adhérent
            $max = mt_rand(1, 5);
            for ($j = 0; $j <= $max; $j++) { // création des prêts
                $pret = new Pret();
                $livre = $this->repoLivre->find(mt_rand(1, 49));
                $pret->setLivre($livre)
                    ->setAdherent($this->getReference("adherent" . $i))
                    ->setDatePret($this->faker->dateTimeBetween('-6 months'));
                $dateRetourPrevue = date('Y-m-d H:m:n', strtotime('15 days', $pret->getDatePret()->getTimestamp()));
                $dateRetourPrevue = \DateTime::createFromFormat('Y-m-d H:m:n', $dateRetourPrevue);
                $pret->setDateRetourPrevue($dateRetourPrevue);
 
                if (mt_rand(1, 3) == 1) {
                    $pret->setDateRetourReelle($this->faker->dateTimeInInterval($pret->getDatePret(), "+30 days"));
                }
                $this->manager->persist($pret);
            }
        }
        $this->manager->flush();
    }
   
}
