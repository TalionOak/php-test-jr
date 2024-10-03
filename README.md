# Live eCommerce - Teste para PHP

# Sistema de Gerenciamento de Biblioteca

## Descrição do Projeto
Desenvolva um sistema simples de gerenciamento de biblioteca que permita adicionar, remover e listar livros, além de gerenciar empréstimos de livros.

## Requisitos do Projeto
1. **Classes e Objetos:**
   - Crie classes para representar os principais componentes do sistema: `Livro`, `Usuario`, `Emprestimo`.
   - Cada classe deve ter atributos e métodos apropriados. Por exemplo, a classe `Livro` pode ter atributos como `titulo`, `autor`, `isbn`, e métodos como `emprestar()` e `devolver()`.

2. **Encapsulamento:**
   - Utilize encapsulamento para proteger os dados das classes. Os atributos devem ser privados e acessados através de métodos públicos (getters e setters).

3. **Herança:**
   - Implemente herança onde for apropriado. Por exemplo, você pode ter uma classe base `Pessoa` e derivar a classe `Usuario` dela.

4. **Polimorfismo:**
   - Utilize polimorfismo para permitir que diferentes tipos de usuários (por exemplo, `Aluno` e `Professor`) possam ser tratados de maneira uniforme.

5. **DDD (Domain-Driven Design):**
   - Estruture o projeto seguindo os princípios de DDD. Crie camadas de domínio, aplicação e infraestrutura.
   - Defina entidades, agregados, repositórios e serviços de domínio.
   - Utilize Value Objects para representar conceitos imutáveis, como ISBN.

6. **Persistência:**
   - Implemente uma camada de persistência simples usando arquivos JSON ou um banco de dados SQLite para armazenar os dados dos livros e usuários.

7. **Publicação no Git:**
   - Crie um repositório no GitHub, Bitbucket ou GitLab e faça commits regulares mostrando o progresso do desenvolvimento.
   - Inclua um arquivo README.md com instruções sobre como configurar e executar o projeto.

## Requisitos

- Todo o código criado precisa conter teste unitário;
- A padronização de sintaxe deve ser PSR-2 (https://www.php-fig.org/psr/psr-2/);
- O código precisa estar devidamente documentado, seguindo o padrão dos arquivos atuais;
- Todo código e documentação deve estar em inglês.
- voce deve criar esse teste em um repositirio proprio do github e enviar o link final do projeto no email recebido pelo noso RH

