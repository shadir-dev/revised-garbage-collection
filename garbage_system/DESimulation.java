import java.util.PriorityQueue;
import java.util.Comparator;

// Event.java
abstract class Event implements Comparable<Event> {
    protected double time;
    public Event(double time) { this.time = time; }
    public double getTime() { return time; }
    public abstract void process(Simulation sim);
    @Override
    public int compareTo(Event o) {
        return Double.compare(this.time, o.time);
    }
}

// EventChain.java
class EventChain {
    private PriorityQueue<Event> pq = new PriorityQueue<>();
    public void add(Event e) { pq.add(e); }
    public Event next() { return pq.poll(); }
    public boolean isEmpty() { return pq.isEmpty(); }
}

// CustomerArrival.java
class CustomerArrival extends Event {
    private final double iaTime = 10.0;  // inter-arrival time
    public CustomerArrival(double time) { super(time); }
    @Override
    public void process(Simulation sim) {
        sim.q++;
        sim.updateMinMax();
        sim.arrivalTimes.add(sim.clock);
        // schedule next arrival
        sim.chain.add(new CustomerArrival(time + iaTime));
        // if server was idle, schedule a completion
        if (sim.q == 1) {
            sim.chain.add(new ServiceCompletion(time));
        }
    }
}

// ServiceCompletion.java
class ServiceCompletion extends Event {
    public ServiceCompletion(double time) { super(time); }
    @Override
    public void process(Simulation sim) {
        // complete one customer
        sim.q--;
        sim.updateMinMax();
        if (sim.q > 0) {
            // schedule next completion
            double st = sim.getServiceTime();
            sim.chain.add(new ServiceCompletion(time + st));
        }
        sim.endServiceTimes.add(sim.clock);
    }
}

// SimulationTermination.java
class SimulationTermination extends Event {
    public SimulationTermination(double time) { super(time); }
    @Override
    public void process(Simulation sim) {
        sim.running = false;
    }
}

// Simulation.java
class Simulation {
    public EventChain chain = new EventChain();
    public double clock = 0.0;
    public int q = 0, minQ = Integer.MAX_VALUE, maxQ = 0;
    public boolean running = true;
    private double serviceTime = 10.0; // Default service time (can be adjusted)
    public boolean serverIdle = true;

    // Track arrival and service completion times for each customer
    public java.util.List<Double> arrivalTimes = new java.util.ArrayList<>();
    public java.util.List<Double> startServiceTimes = new java.util.ArrayList<>();
    public java.util.List<Double> endServiceTimes = new java.util.ArrayList<>();

    public Simulation(double serviceTime) {
        this.serviceTime = serviceTime;
    }

    public void updateMinMax() {
        minQ = Math.min(minQ, q);
        maxQ = Math.max(maxQ, q);
    }

    public double getServiceTime() {
        return serviceTime;
    }

    public void run(double T) {
        // reset state
        clock = 0.0;
        q = 0;
        minQ = Integer.MAX_VALUE;
        maxQ = 0;
        running = true;
        serverIdle = true;
        arrivalTimes.clear();
        startServiceTimes.clear();
        endServiceTimes.clear();

        // schedule initial events
        chain.add(new CustomerArrival(0.0)); // First arrival at time 0
        chain.add(new SimulationTermination(T));

        // event loop
        while (running && !chain.isEmpty()) {
            Event e = chain.next();
            clock = e.getTime();
            e.process(this);
        }

        // Calculate performance metrics
        calculatePerformanceMetrics();
    }

    private void calculatePerformanceMetrics() {
        if (arrivalTimes.isEmpty() || endServiceTimes.isEmpty()) return;

        double totalWaitingTime = 0.0;
        double totalIdleTime = 0.0;
        double lastServiceEndTime = 0.0;

        for (int i = 0; i < arrivalTimes.size(); i++) {
            double arrivalTime = arrivalTimes.get(i);
            double startServiceTime = startServiceTimes.get(i);
            double endServiceTime = endServiceTimes.get(i);

            // Calculate waiting time for the customer
            double waitingTime = startServiceTime - arrivalTime;
            totalWaitingTime += waitingTime;

            // Calculate idle time if this is not the first customer
            if (i > 0) {
                totalIdleTime += Math.max(0, startServiceTime - lastServiceEndTime);
            }

            lastServiceEndTime = endServiceTime;
        }

        double averageWaitingTime = totalWaitingTime / arrivalTimes.size();
        double averageIdleTime = totalIdleTime / arrivalTimes.size();
        double averageServiceTime = serviceTime;

        System.out.println("Simulation Results:");
        System.out.println("Average Waiting Time: " + averageWaitingTime);
        System.out.println("Average Idle Time: " + averageIdleTime);
        System.out.println("Average Service Time: " + averageServiceTime);
        System.out.println("Minimum Queue Length: " + minQ);
        System.out.println("Maximum Queue Length: " + maxQ);
    }

    public static void main(String[] args) {
        Simulation sim = new Simulation(10.0); // Default service time of 10s
        sim.run(10000.0); // Run simulation for 10,000 seconds
    }
}