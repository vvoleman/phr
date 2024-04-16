#!/bin/bash

sizes=(100 500 1000 5000)
commands=("nrpzs" "sukl" "mkn")

# Loop 3 times
for k in {1..4}; do

  # Loop for each command
  for command_name in "${commands[@]}"; do
    file_name="test/$k.$command_name.txt"
    echo -n "" >"$file_name"

    # Loop over batch sizes
    for i in "${sizes[@]}"; do
      # Set ENV "BATCH_SIZE" to current size
      export BATCH_SIZE=$i
      echo "Starting (k:$k) $command_name $i"
      # Drop tables
      php bin/console doctrine:query:sql "$(<drop.sql)"

      # Execute migration command
      php bin/console doctrine:migrations:migrate --no-interaction

      # Execute syncer:run:all command and output to test_clear_%d.txt
      echo "Batch: $i" >>"$file_name"
      php bin/console syncer:"$command_name":run --no-debug >>"$file_name"
    done
  done
done
