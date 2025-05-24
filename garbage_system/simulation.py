import random

def generate_normal(mean, sd):
    # Box-Muller transform approximation using Central Limit Theorem
    sum_vals = sum(random.random() for _ in range(12))
    return mean + sd * (sum_vals - 6)

def single_queue_simulation(run=10):
    mean_iat = 10.0
    sd_iat = 1.5
    mean_st = 9.5
    sd_st = 1.0

    cat = 0.0  # Cumulative Arrival Time
    se = 0.0   # Service End Time
    cwt = 0.0  # Cumulative Waiting Time
    cit = 0.0  # Cumulative Idle Time

    # Print header with proper column width for alignment
    print(f"{'i':<3}{'IAT':<10}{'CAT':<10}{'SB':<10}{'ST':<10}{'SE':<10}{'WT':<10}{'IT':<10}")

    for j in range(1, run + 1):
        iat = generate_normal(mean_iat, sd_iat)
        cat += iat

        if cat <= se:
            sb = se
            wt = se - cat
            cwt += wt
            it = 0.0
        else:
            sb = cat
            wt = 0.0
            it = sb - se
            cit += it

        st = generate_normal(mean_st, sd_st)
        se = sb + st

        # Print each line of the table with consistent column width
        print(f"{j:<3}{iat:<10.3f}{cat:<10.3f}{sb:<10.3f}{st:<10.3f}{se:<10.3f}{wt:<10.3f}{it:<10.3f}")

    # Calculate average waiting time and percentage capacity utilization
    awt = cwt / run
    pcu = ((cat - cit) * 100) / cat

    # Print final results
    print(f"\n{'Average Waiting Time:':<20}{awt:.3f}")
    print(f"{'Percentage Capacity Utilization:':<20}{pcu:.2f}%")

# Run the simulation
single_queue_simulation()
