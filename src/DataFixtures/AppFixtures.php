<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\RubiksCube;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Créer les utilisateurs
        $admin = new User();
        $admin->setUsername('admin');
        $admin->setEmail('admin@collection.com');
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'admin123'));
        $admin->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);

        $user1 = new User();
        $user1->setUsername('demo');
        $user1->setEmail('demo@rubikscube.com');
        $user1->setPassword($this->passwordHasher->hashPassword($user1, 'demo123'));
        $manager->persist($user1);

        $user2 = new User();
        $user2->setUsername('collector');
        $user2->setEmail('collector@rubikscube.com');
        $user2->setPassword($this->passwordHasher->hashPassword($user2, 'collector123'));
        $manager->persist($user2);

        // Créer les Rubik's Cubes

        // 1. Rubik's Cube 3x3 Classic
        $cube1 = new RubiksCube();
        $cube1->setName("Rubik's Cube 3x3 Original");
        $cube1->setType("3x3");
        $cube1->setBrand("Rubik's");
        $cube1->setDescription("Le cube classique original inventé par Ernő Rubik en 1974. Ce puzzle mécanique tridimensionnel est composé de 26 petits cubes et possède 43 quintillions de combinaisons possibles. Parfait pour les débutants.");
        $cube1->setDifficulty("Débutant");
        $cube1->setReleaseYear(1980);
        $cube1->setImageUrl("https://upload.wikimedia.org/wikipedia/commons/thumb/a/a6/Rubik%27s_cube.svg/1200px-Rubik%27s_cube.svg.png");
        $manager->persist($cube1);

        // 2. GAN 356 X
        $cube2 = new RubiksCube();
        $cube2->setName("GAN 356 X");
        $cube2->setType("3x3");
        $cube2->setBrand("GAN");
        $cube2->setDescription("Cube 3x3 haut de gamme avec système de réglage magnétique numérique. Utilisé par de nombreux champions de speedcubing. Rotation ultra-fluide et personnalisable avec 6 niveaux de tension et élasticité.");
        $cube2->setDifficulty("Expert");
        $cube2->setReleaseYear(2019);
        $cube2->setImageUrl("https://cdn.speedcubeshop.com/9a1c9f04a4c6ec1c4a4f1e2e9a0f8e8e/gan-356-x-3x3-stickerless.jpg");
        $manager->persist($cube2);

        // 3. MoYu RS3 M 2020
        $cube3 = new RubiksCube();
        $cube3->setName("MoYu RS3 M 2020");
        $cube3->setType("3x3");
        $cube3->setBrand("MoYu");
        $cube3->setDescription("Excellent cube magnétique pour débutants et intermédiaires. Rapport qualité-prix exceptionnel avec des performances proches des cubes haut de gamme. Rotation stable et contrôlable grâce aux aimants.");
        $cube3->setDifficulty("Intermédiaire");
        $cube3->setReleaseYear(2020);
        $cube3->setImageUrl("https://cdn.speedcubeshop.com/95b8f6d7c5e4b3a2d1f0e9c8a7b6d5e4/moyu-rs3-m-2020-3x3-stickerless.jpg");
        $manager->persist($cube3);

        // 4. QiYi Warrior W
        $cube4 = new RubiksCube();
        $cube4->setName("QiYi Warrior W");
        $cube4->setType("3x3");
        $cube4->setBrand("QiYi");
        $cube4->setDescription("Cube d'entrée de gamme parfait pour débuter dans le speedcubing. Léger, rapide et abordable. Idéal pour apprendre les algorithmes de base et améliorer ses temps.");
        $cube4->setDifficulty("Débutant");
        $cube4->setReleaseYear(2017);
        $cube4->setImageUrl("https://cdn.speedcubeshop.com/qiyi-warrior-w-3x3-stickerless.jpg");
        $manager->persist($cube4);

        // 5. YuXin Little Magic 2x2
        $cube5 = new RubiksCube();
        $cube5->setName("YuXin Little Magic 2x2");
        $cube5->setType("2x2");
        $cube5->setBrand("YuXin");
        $cube5->setDescription("Pocket Cube économique avec d'excellentes performances. Plus simple que le 3x3 avec seulement 3,674,160 combinaisons. Parfait pour débuter ou comme cube secondaire.");
        $cube5->setDifficulty("Débutant");
        $cube5->setReleaseYear(2017);
        $cube5->setImageUrl("https://cdn.speedcubeshop.com/yuxin-little-magic-2x2-stickerless.jpg");
        $manager->persist($cube5);

        // 6. QiYi MS 4x4
        $cube6 = new RubiksCube();
        $cube6->setName("QiYi MS 4x4 Magnetic");
        $cube6->setType("4x4");
        $cube6->setBrand("QiYi");
        $cube6->setDescription("Cube 4x4 magnétique abordable avec d'excellentes performances. Plus complexe que le 3x3, il nécessite la résolution des centres et l'appariement des arêtes. 7.4 × 10^45 combinaisons possibles.");
        $cube6->setDifficulty("Intermédiaire");
        $cube6->setReleaseYear(2020);
        $cube6->setImageUrl("https://cdn.speedcubeshop.com/qiyi-ms-4x4-magnetic-stickerless.jpg");
        $manager->persist($cube6);

        // 7. YJ MGC 5x5
        $cube7 = new RubiksCube();
        $cube7->setName("YJ MGC 5x5 Magnetic");
        $cube7->setType("5x5");
        $cube7->setBrand("YJ");
        $cube7->setDescription("Cube 5x5 magnétique performant. Avec ses 282 870 942 277 741 856 536 180 333 107 150 328 293 127 731 985 672 134 721 536 000 000 000 000 000 combinaisons, c'est un défi de taille !");
        $cube7->setDifficulty("Expert");
        $cube7->setReleaseYear(2019);
        $cube7->setImageUrl("https://cdn.speedcubeshop.com/yj-mgc-5x5-magnetic-stickerless.jpg");
        $manager->persist($cube7);

        // 8. QiYi Pyraminx
        $cube8 = new RubiksCube();
        $cube8->setName("QiYi MS Pyraminx");
        $cube8->setType("Pyraminx");
        $cube8->setBrand("QiYi");
        $cube8->setDescription("Puzzle en forme de tétraèdre avec 4 faces triangulaires. Plus facile que le 3x3, il est parfait pour varier les plaisirs. 75 582 720 combinaisons possibles (933 120 en ignorant les rotations triviales).");
        $cube8->setDifficulty("Débutant");
        $cube8->setReleaseYear(2020);
        $cube8->setImageUrl("https://cdn.speedcubeshop.com/qiyi-ms-pyraminx-magnetic-stickerless.jpg");
        $manager->persist($cube8);

        // 9. YuXin Little Magic Megaminx
        $cube9 = new RubiksCube();
        $cube9->setName("YuXin Little Magic Megaminx");
        $cube9->setType("Megaminx");
        $cube9->setBrand("YuXin");
        $cube9->setDescription("Puzzle dodécaédrique avec 12 faces pentagonales. Version plus complexe du 3x3 qui demande patience et mémoire. Environ 1.01 × 10^68 combinaisons possibles. Un vrai défi !");
        $cube9->setDifficulty("Expert");
        $cube9->setReleaseYear(2018);
        $cube9->setImageUrl("https://cdn.speedcubeshop.com/yuxin-little-magic-megaminx-stickerless.jpg");
        $manager->persist($cube9);

        // 10. QiYi Skewb
        $cube10 = new RubiksCube();
        $cube10->setName("QiYi Wingy Skewb");
        $cube10->setType("Skewb");
        $cube10->setBrand("QiYi");
        $cube10->setDescription("Puzzle cubique qui tourne sur ses coins. Mécanisme de rotation unique et différent du Rubik's Cube classique. 3 149 280 combinaisons possibles. Amusant et accessible.");
        $cube10->setDifficulty("Intermédiaire");
        $cube10->setReleaseYear(2019);
        $cube10->setImageUrl("https://cdn.speedcubeshop.com/qiyi-wingy-skewb-magnetic-stickerless.jpg");
        $manager->persist($cube10);

        // 11. MoYu WeiLong WR M
        $cube11 = new RubiksCube();
        $cube11->setName("MoYu WeiLong WR M 2021");
        $cube11->setType("3x3");
        $cube11->setBrand("MoYu");
        $cube11->setDescription("Cube flagship de MoYu utilisé par de nombreux records du monde. Maglev (lévitation magnétique) pour une rotation ultra-rapide. Choix des champions de speedcubing.");
        $cube11->setDifficulty("Expert");
        $cube11->setReleaseYear(2021);
        $cube11->setImageUrl("https://cdn.speedcubeshop.com/moyu-weilong-wr-m-2021-3x3-maglev-stickerless.jpg");
        $manager->persist($cube11);

        // 12. ShengShou Mirror Cube
        $cube12 = new RubiksCube();
        $cube12->setName("ShengShou Mirror Cube");
        $cube12->setType("3x3");
        $cube12->setBrand("ShengShou");
        $cube12->setDescription("Cube 3x3 shape-shifting doré ou argenté. La résolution se base sur la forme plutôt que sur les couleurs. Challenge unique qui change du cube classique. Très esthétique.");
        $cube12->setDifficulty("Intermédiaire");
        $cube12->setReleaseYear(2015);
        $cube12->setImageUrl("https://cdn.speedcubeshop.com/shengshou-mirror-cube-3x3-gold.jpg");
        $manager->persist($cube12);

        // 13. YJ MGC 6x6
        $cube13 = new RubiksCube();
        $cube13->setName("YJ MGC 6x6 Magnetic");
        $cube13->setType("6x6");
        $cube13->setBrand("YJ");
        $cube13->setDescription("Grand cube 6x6 magnétique pour les passionnés de gros cubes. Environ 1.57 × 10^116 combinaisons. Nécessite endurance et concentration. Performance exceptionnelle pour un 6x6.");
        $cube13->setDifficulty("Expert");
        $cube13->setReleaseYear(2020);
        $cube13->setImageUrl("https://cdn.speedcubeshop.com/yj-mgc-6x6-magnetic-stickerless.jpg");
        $manager->persist($cube13);

        // 14. QiYi Square-1
        $cube14 = new RubiksCube();
        $cube14->setName("QiYi X-Man Volt Square-1");
        $cube14->setType("Square-1");
        $cube14->setBrand("QiYi");
        $cube14->setDescription("Puzzle cubique avec des couches qui peuvent changer de forme. Mécanisme unique permettant des rotations partielles. 435 891 456 000 combinaations. Un vrai casse-tête original !");
        $cube14->setDifficulty("Expert");
        $cube14->setReleaseYear(2020);
        $cube14->setImageUrl("https://cdn.speedcubeshop.com/qiyi-xman-volt-square1-magnetic-stickerless.jpg");
        $manager->persist($cube14);

        // 15. MoYu Puppet Cube
        $cube15 = new RubiksCube();
        $cube15->setName("MoYu Puppet Cube");
        $cube15->setType("3x3");
        $cube15->setBrand("MoYu");
        $cube15->setDescription("Cube 3x3 modifié avec des pièces de formes différentes. Variante amusante du cube classique qui ajoute un défi visuel. Les algorithmes du 3x3 s'appliquent mais avec un twist supplémentaire.");
        $cube15->setDifficulty("Intermédiaire");
        $cube15->setReleaseYear(2018);
        $cube15->setImageUrl("https://cdn.speedcubeshop.com/moyu-puppet-cube-stickerless.jpg");
        $manager->persist($cube15);

        $manager->flush();
    }
}
