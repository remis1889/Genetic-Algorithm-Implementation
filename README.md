# Genetic-Algorithm-Implementation
Implements the basic functions of GA using PHP

Genetic algorithms (GAs) were invented by John Holland in the 1960s. GA is a search heuristic that mimics the process of natural evolution. This heuristic is routinely used to generate useful solutions to optimization and search problems. Algorithm is started with a set of solutions (represented by chromosomes) called population. Solutions from one population are taken and used to form a new population. This is motivated by a hope, that the new population will be better than the old one. Solutions which are selected to form new solutions (offspring) are selected according to their fitness - the more suitable they are the more chances they have to reproduce. This is repeated until some condition (for example number of populations or improvement of the best solution) is satisfied.


Outline of the basic genetic algorithm is as follows:

1) Chromosome Encoding : Generate random population of n chromosomes (suitable solutions for the problem)

2) Fitness Function : Evaluate the fitness f(x) of each chromosome x in the population

3) New Population : Create a new population by repeating following steps until the new population is complete
	a) Selection : Select two parent chromosomes from a population according to their fitness (the better fitness, the bigger chance to be selected)
	b) Crossover/Recombination : With a crossover probability cross over the parents to form a new offspring (children). If no crossover was performed, offspring is an exact copy of parents.
	c) Mutation : With a mutation probability mutate new offspring at each locus (position in chromosome).
	d) Place new offspring in a new population

4) Use new generated population for a further run of algorithm

5) If the end condition is satisfied, stop, and return the best solution in current population

6) Repeat steps 2 to 5 until termination condition is satisfied