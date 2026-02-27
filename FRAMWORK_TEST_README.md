<p align="center">
  <h1 align="center">FactoryAI</h1>
  <p align="center">
    A multi-agent software factory powered by AI
  </p>
</p>

<p align="center">
  <a href="#quick-start">Quick Start</a> |
  <a href="./docs/getting-started.md">Getting Started</a> |
  <a href="./docs/architecture.md">Architecture</a> |
  <a href="./docs/agents-guide.md">Agents Guide</a> |
  <a href="./docs/configuration.md">Configuration</a> |
  <a href="./CONTRIBUTING.md">Contributing</a>
</p>

---

## What is FactoryAI?

FactoryAI is an open-source, multi-agent orchestration framework that models a complete software engineering organization. It coordinates a team of specialized AI agents -- CEO, CTO, Product Manager, Tech Lead, Developer, QA Engineer, and Code Reviewer -- to collaboratively analyze, plan, implement, and review software tasks.

Give FactoryAI a high-level directive in natural language, and it will break the work down through a realistic organizational hierarchy: the CEO interprets the vision, the CTO designs the architecture, the Product Manager writes requirements, the Tech Lead decomposes tasks, the Developer writes code, the QA Engineer validates quality, and the Code Reviewer inspects every change.

## Key Features

- **Multi-Agent Architecture** -- Seven specialized agents with distinct roles, personalities, and responsibilities that mirror a real engineering team.
- **Phase-Based Orchestration** -- Work flows through six structured phases: Analysis, Design, Planning, Execution, Review, and Delivery.
- **Agent Handoffs** -- Structured data handoffs between agents ensure context is preserved as work moves through the pipeline.
- **Budget Controls** -- Built-in token and cost tracking with configurable limits per run, per agent, alert thresholds, and automatic pause-on-limit.
- **Multi-Provider Support** -- First-class support for Anthropic (Claude) and OpenAI (GPT) models, with a pluggable provider interface for adding more.
- **Concurrent Execution** -- A worker pool enables parallel agent execution during the implementation phase, with configurable concurrency limits.
- **Built-in Tools** -- Agents have access to filesystem, git, terminal, code analysis, and test runner tools out of the box.
- **Event-Driven** -- A typed event bus lets you observe every agent action, task transition, handoff, budget alert, and phase change in real time.
- **CLI Interface** -- A full command-line interface with `init`, `run`, `plan`, `review`, `status`, and `docs` commands.
- **YAML Configuration** -- All settings are managed through a single `.factoryai/config.yml` file with environment variable overrides.
- **TypeScript Monorepo** -- Written entirely in TypeScript with strict mode, organized as a pnpm workspace with five packages.

## Architecture Overview

```
                           +----------------+
                           |      CLI       |
                           |  (factoryai)   |
                           +-------+--------+
                                   |
                                   v
                          +--------+---------+
                          |   Orchestrator   |
                          |  (phase runner)  |
                          +--------+---------+
                                   |
              +--------------------+--------------------+
              |                    |                    |
              v                    v                    v
        +-----------+       +-----------+       +-----------+
        |  Planner  |       |  Worker   |       |  Budget   |
        |           |       |   Pool    |       | Controller|
        +-----------+       +-----------+       +-----------+
                                   |
              +--------------------+--------------------+
              |          |         |         |          |
              v          v         v         v          v
          +------+  +------+  +------+  +------+  +--------+
          | CEO  |  | CTO  |  |  PM  |  | Dev  |  |  QA /  |
          |      |  |      |  |      |  |      |  |Reviewer|
          +------+  +------+  +------+  +------+  +--------+
              |          |         |         |          |
              v          v         v         v          v
          +-------+  +-------+  +-------+  +-------+  +-------+
          |Handoff|->|Handoff|->|Handoff|->|Handoff|->|Handoff|
          +-------+  +-------+  +-------+  +-------+  +-------+
                                   |
                          +--------+---------+
                          |    LLM Provider  |
                          | (Anthropic/OpenAI)|
                          +------------------+
```

**Phase Flow:**

```
Analysis --> Design --> Planning --> Execution --> Review --> Delivery
  (CEO)      (CTO)    (PM + TL)     (Dev)      (QA + CR)    (CEO)
```

## Monorepo Structure

```
factoryai/
  packages/
    core/        - Orchestrator, Agent, Config, Budget, Events, Handoffs, Types
    agents/      - Agent role definitions (CEO, CTO, PM, TL, Dev, QA, Reviewer)
    providers/   - LLM provider adapters (Anthropic, OpenAI)
    tools/       - Agent tools (Filesystem, Git, Terminal, Analyzer, TestRunner)
    cli/         - Command-line interface (init, run, plan, review, status, docs)
  templates/
    config.yml   - Default configuration template
    prompts/     - Default agent prompt templates
```

## Quick Start

### Prerequisites

- Node.js >= 20.0.0
- pnpm >= 9.0.0
- An API key for Anthropic or OpenAI

### Installation

```bash
# Clone the repository
git clone https://github.com/factoryai/factoryai.git
cd factoryai

# Install dependencies
pnpm install

# Build all packages
pnpm build
```

### Initialize a Project

```bash
# Navigate to your project directory
cd /path/to/your/project

# Initialize FactoryAI
factoryai init --name "my-project" --provider anthropic

# Set your API key
export ANTHROPIC_API_KEY="sk-ant-..."
```

### Run a Directive

```bash
# Execute a full agent workflow
factoryai run "Add a user authentication system with JWT tokens"

# Or just create a plan without executing
factoryai plan "Refactor the database layer to use connection pooling"
```

## CLI Commands Reference

| Command | Description |
|---|---|
| `factoryai init` | Initialize FactoryAI in the current project. Creates `.factoryai/` directory with config, logs, handoffs, and prompts. |
| `factoryai run <directive>` | Run the full multi-agent workflow for a given directive. Passes through all six phases. |
| `factoryai plan <directive>` | Create an execution plan without running it. Uses CEO, CTO, PM, and Tech Lead agents. |
| `factoryai review` | Review current uncommitted git changes with the Code Reviewer agent. |
| `factoryai status` | Display the FactoryAI status dashboard: configuration, agent states, and recent handoffs. |
| `factoryai docs` | Generate project documentation based on the current project structure and package.json. |

### Init Options

```bash
factoryai init [options]

  -n, --name <name>         Project name (auto-detected from package.json)
  -p, --provider <provider>  LLM provider: "anthropic" or "openai" (default: "anthropic")
```

## Configuration

FactoryAI is configured via `.factoryai/config.yml`. See the [Configuration Reference](./docs/configuration.md) for full details.

```yaml
project:
  name: "my-project"
  description: "A brief description"

provider:
  name: "anthropic"
  model: "claude-sonnet-4-20250514"

budget:
  maxTokensPerRun: 1000000
  maxCostPerRun: 10.0
  alertThreshold: 0.8
  pauseOnLimit: true
```

API keys can also be set via environment variables:

```bash
export ANTHROPIC_API_KEY="sk-ant-..."    # For Anthropic
export OPENAI_API_KEY="sk-..."           # For OpenAI
export FACTORYAI_PROVIDER="anthropic"    # Override provider
export FACTORYAI_MODEL="claude-sonnet-4-20250514"  # Override model
```

## Documentation

| Document | Description |
|---|---|
| [Getting Started](./docs/getting-started.md) | Installation, initialization, and first run walkthrough |
| [Architecture](./docs/architecture.md) | Deep dive into the multi-agent architecture and data flow |
| [Agents Guide](./docs/agents-guide.md) | Detailed reference for each agent's role, personality, and behavior |
| [Configuration](./docs/configuration.md) | Complete `config.yml` reference with all options |
| [Plugins](./docs/plugins.md) | Guide to the plugin system: providers, tools, and extensions |
| [Custom Agents](./docs/custom-agents.md) | How to create and register custom agent roles |
| [Scope](./SCOPE.md) | Project scope, goals, and non-goals |
| [Contributing](./CONTRIBUTING.md) | How to contribute to FactoryAI |
| [Changelog](./CHANGELOG.md) | Release history and version notes |

## Contributing

Contributions are welcome. Please read the [Contributing Guide](./CONTRIBUTING.md) before opening a pull request.

```bash
# Development workflow
pnpm install          # Install dependencies
pnpm build            # Build all packages
pnpm test             # Run all tests
pnpm lint             # Lint all packages
pnpm format           # Format code with Prettier
pnpm typecheck        # Run TypeScript type checking
```

## License

FactoryAI is released under the [MIT License](./LICENSE).

Copyright (c) 2026 FactoryAI Contributors.
