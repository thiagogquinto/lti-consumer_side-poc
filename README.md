# LTI Consumer - POC

Este repositório busca realizar a implementação de um *LTI Consumer* (também conhecido como *LTI Platform*) utilizando a biblioteca [TAO - LTI 1.3 PHP Framework](https://oat-sa.github.io/doc-lti1p3/). Nesta implamentação, o *LTI Consumer* atuará como uma plataforma que inicia uma solicitação de lançamento para um *LTI Tool* (Moodle).

# Passo a Passo

## Moodle (Tool)

Para configurar o Moodle como um *LTI Tool*, foram seguidas as recomendações presentes [aqui](https://docs.moodle.org/500/en/Publish_as_LTI_tool).

## Consumer Side

### Estrutura do Projeto

A estrutura do projeto é a seguinte:

- `vendor/`: Diretório contendo as dependências do projeto, gerenciadas pelo Composer
- `keys.php`: Contém as chaves públicas e privadas utilizadas para a assinatura e verificação dos tokens JWT.
- `platform.php`: Configura o *LTI Consumer* e define os parâmetros necessários para a comunicação com o *LTI Tool*.
- `tool.php`: Define as configurações específicas do *LTI Tool*, como o URL de lançamento e o identificador do cliente.
- `registration.php`: Gerencia o registro do *LTI Tool* no *LTI Consumer*, incluindo a obtenção e armazenamento das chaves necessárias.
- `launch.php`: Ponto de entrada para iniciar o lançamento do LTI. Este arquivo cria a solicitação de lançamento e redireciona o usuário para o *LTI Tool*.
- `registrationRepository.php`: Implementa a interface `RegistrationRepository` da biblioteca TAO para gerenciar o armazenamento e recuperação das informações de registro do *LTI Tool*.
- `userAuthenticator.php`: Implementa a interface `UserAuthenticator` da biblioteca TAO para autenticar usuários durante o processo de lançamento do LTI.
- `jwks/certs.php`: Armazena as chaves públicas em formato JWKS (JSON Web Key Set) para validação dos tokens JWT.
- `oidc/authentication.php`: Gerencia o fluxo de autenticação OpenID Connect (OIDC) necessário para o lançamento do LTI - **A TERMINAR**
- `token/token.php`: Gerencia a criação e validação dos tokens JWT utilizados no processo de lançamento do LTI - **A FAZER**

Os valores de configuração, como URLs, identificadores e chaves, são definidos nos arquivos `platform.php` e `tool.php`. Esses valores devem ser ajustados conforme o ambiente e as necessidades específicas do projeto, seguindo também os valores registrados no Moodle.