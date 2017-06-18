<?php

namespace AppBundle\Helper;

use AppBundle\Entity\Recipe;
use AppBundle\Entity\RecipeProduct;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class ANNHelper
{
    private $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    public function learnNetwork(Recipe $recipe, User $user){
        //////////// start of straight propagation /////////////////
        // weight
        $weights = $this->getANNWeights($user);
        $IH1Weights = $weights["IH1Weights"];
        $IH2Weights = $weights["IH2Weights"];
        $H1OWeight = $weights["H1OWeight"];
        $H2OWeight = $weights["H2OWeight"];

        //values
        $IValues = [];

        //hyperparameters
        $E = 1; // speed
        $a = 1; // moment

        //deltas
        $H1OWeightDelta = 0;
        $H2OWeightDelta = 0;
        $IH1WeightsDelta = [];
        $IH2WeightsDelta = [];

        if(!count($IH1Weights)) {
            //initialization weight and values
            $products = $this->em->getRepository(RecipeProduct::class)->getNameAllProducts();

            foreach ($products as $product) {
                $IH1Weights[$product["name"]] = mt_rand() / mt_getrandmax();;
                $IH2Weights[$product["name"]] = mt_rand() / mt_getrandmax();;
                $IH1WeightsDelta[$product["name"]] = 0;
                $IH2WeightsDelta[$product["name"]] = 0;
                $IValues[$product["name"]] = 0;
            }
        }
        else{
            foreach ($IH1Weights as $name => $product) {
                $IValues[$name] = 0;
            }
        }

        /** @var RecipeProduct $product */
        foreach($recipe->getProducts() as $product){
            $IValues[$product->getName()] = 1;
        }

        //work with hidden layer
        $H1i = 0;
        $H2i = 0;

        foreach($IH1Weights as $name => $weight){ // find input on hidden layer
            $H1i += $IValues[$name] * $IH1Weights[$name];
            $H2i += $IValues[$name] * $IH2Weights[$name];
        }

        $H1o = $this->functionActivation($H1i); // find output on hidden layer
        $H2o = $this->functionActivation($H2i);

        //work with output layer
        $Oi = $H1o * $H1OWeight + $H2o * $H2OWeight; //find input on output layer

        $Oo = $this->functionActivation($Oi); // find output on output layer

        //////////// end of straight propagation /////////////////

        //////////// start of back propagation /////////////////
        while($this->errorMSE($Oo, 1) > 0.05){
            // find delta for output layer
            $deltaO = (1 - $Oo) * $this->derivativeFunctionActivation($Oi); // correction for output neuron
            // find delta for hidden layer
            $deltaH1 = $this->derivativeFunctionActivation($H1i) * $H1OWeight * $deltaO; // correction for hidden neuron 1
            $deltaH2 = $this->derivativeFunctionActivation($H2i) * $H2OWeight * $deltaO; // correction for hidden neuron 2

            // find gradient for hidden -> output sinapses
            $gradH1O = $deltaO * $H1o;
            $gradH2O = $deltaO * $H2o;

            $gradIH1 = [];
            $gradIH2 = [];

            // find gradient for input -> hidden sinapses
            foreach($IH2WeightsDelta as $name => $delta){
                $gradIH1[$name] = $deltaH1 * $IValues[$name];
                $gradIH2[$name] = $deltaH2 * $IValues[$name];
            }

            // find weight delta for hidden -> output sinapses
            $H1OWeightDelta = $E * $gradH1O + $a * $H1OWeightDelta;
            $H2OWeightDelta = $E * $gradH2O + $a * $H2OWeightDelta;

            // find weight delta for input -> hidden sinapses
            foreach($gradIH1 as $name => $grad){
                $IH1WeightsDelta[$name] = $E * $gradIH1[$name] + $a * $IH1WeightsDelta[$name];
                $IH2WeightsDelta[$name] = $E * $gradIH2[$name] + $a * $IH2WeightsDelta[$name];
            }

            //correct weights
            $H1OWeight += $H1OWeightDelta;
            $H2OWeight += $H2OWeightDelta;

            foreach($IH1WeightsDelta as $name => $weightDelta){
                $IH1Weights[$name] += $IH1WeightsDelta[$name];
                $IH2Weights[$name] += $IH2WeightsDelta[$name];
            }

            //work with hidden layer
            $H1i = 0;
            $H2i = 0;

            foreach($IH1Weights as $name => $weight){ // find input on hidden layer
                $H1i += $IValues[$name] * $IH1Weights[$name];
                $H2i += $IValues[$name] * $IH2Weights[$name];
            }

            $H1o = $this->functionActivation($H1i); // find output on hidden layer
            $H2o = $this->functionActivation($H2i);

            //work with output layer
            $Oi = $H1o * $H1OWeight + $H2o * $H2OWeight; //find input on output layer

            $Oo = $this->functionActivation($Oi); // find output on output layer
        }

        $this->saveWeights($IH1Weights, $IH2Weights, $H1OWeight, $H2OWeight, $user);
    }

    public function getANNRecipeResult(User $user, Recipe $recipe){
        // weight
        $weights = $this->getANNWeights($user);
        $IH1Weights = $weights["IH1Weights"];
        $IH2Weights = $weights["IH2Weights"];
        $H1OWeight = $weights["H1OWeight"];
        $H2OWeight = $weights["H2OWeight"];

        if(!count($IH1Weights)){
            return 1;
        }

        $IValues = [];

        foreach ($IH1Weights as $name => $product) {
            $IValues[$name] = 0;
        }

        /** @var RecipeProduct $product */
        foreach($recipe->getProducts() as $product){
            $IValues[$product->getName()] = 1;
        }

        //work with hidden layer
        $H1i = 0;
        $H2i = 0;

        foreach($IH1Weights as $name => $weight){ // find input on hidden layer
            $H1i += $IValues[$name] * $IH1Weights[$name];
            $H2i += $IValues[$name] * $IH2Weights[$name];
        }

        $H1o = $this->functionActivation($H1i); // find output on hidden layer
        $H2o = $this->functionActivation($H2i);

        //work with output layer
        $Oi = $H1o * $H1OWeight + $H2o * $H2OWeight; //find input on output layer

        return $this->functionActivation($Oi); // find output on output layer
    }

    protected function functionActivation($x){ //sigmoid
        return 1/(1 + exp(-$x));
    }

    protected function derivativeFunctionActivation($x){ // derivative of the sigmoid
        return exp(-$x)/pow($this->functionActivation($x), 2);
    }

    protected function errorMSE($actual, $expected){ // error
        return pow($expected - $actual, 2);
    }

    protected function getANNWeights(User $user){
        $weights = $user->getAnnWeights();

        $result = [
            "IH1Weights" => [],
            "IH2Weights" => [],
            "H1OWeight" => mt_rand() / mt_getrandmax(),
            "H2OWeight" => mt_rand() / mt_getrandmax(),
        ];

        if(!$weights){
            return $result;
        }

        $decodedWeights = json_decode($weights, true);

        $result["IH1Weights"] = $decodedWeights["IH1Weights"];
        $result["IH2Weights"] = $decodedWeights["IH2Weights"];
        $result["H1OWeight"] = $decodedWeights["H1OWeight"];
        $result["H2OWeight"] = $decodedWeights["H2OWeight"];

        return $result;

    }

    protected function saveWeights($IH1Weights, $IH2Weights, $H1OWeight, $H2OWeight, User $user){
        $weights = [
            "IH1Weights" => $IH1Weights,
            "IH2Weights" => $IH2Weights,
            "H1OWeight" => $H1OWeight,
            "H2OWeight" => $H2OWeight,
        ];

        $user->setAnnWeights(json_encode($weights));
        $this->em->flush();
    }
}