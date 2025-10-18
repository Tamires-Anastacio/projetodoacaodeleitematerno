import React from "react";
import { Button, InputGroup, Form } from "react-bootstrap";

function FormCad() {
  return (
    <div className="teses">
      <h1 className="protest-guerrilla-regular mb-4">Cadastro</h1>
      <Form action="../backend/cadastro.php" method="post">
        <Form.Group className="mb-3">
          <Form.Label className="comic-relief-regular">
            Nome Completo
          </Form.Label>
          <Form.Control type="text" name="nome" id="nome" required />
        </Form.Group>

        <Form.Group className="mb-3">
          <Form.Label className="comic-relief-regular">CPF</Form.Label>
          <Form.Control type="text" name="cpf" id="cpf" required />
        </Form.Group>

        <Form.Group className="mb-3">
          <Form.Label className="comic-relief-regular">Telefone</Form.Label>
          <Form.Control
            type="tel"
            name="telefone"
            id="fone"
            maxLength="15"
            placeholder="(xx) xxxxx-xxxx"
            required
          />
        </Form.Group>
        <div className="mid">
          <Form.Group className="mb-3">
            <Form.Label className="comic-relief-regular">
              Data de Nascimento
            </Form.Label>
            <Form.Control
              type="date"
              name="datanascimento"
              id="datanasc"
              required
            />
          </Form.Group>

          <Form.Group className="mb-3">
            <Form.Label className="comic-relief-regular">UF</Form.Label>
            <Form.Select name="uf" id="uf" required>
              <option value="">Selecione</option>
              <option value="AC">AC</option>
              <option value="AL">AL</option>
              <option value="AP">AP</option>
              <option value="AM">AM</option>
              <option value="BA">BA</option>
              <option value="CE">CE</option>
              <option value="DF">DF</option>
              <option value="ES">ES</option>
              <option value="GO">GO</option>
              <option value="MA">MA</option>
              <option value="MT">MT</option>
              <option value="MS">MS</option>
              <option value="MG">MG</option>
              <option value="PA">PA</option>
              <option value="PB">PB</option>
              <option value="PR">PR</option>
              <option value="PE">PE</option>
              <option value="PI">PI</option>
              <option value="RJ">RJ</option>
              <option value="RN">RN</option>
              <option value="RS">RS</option>
              <option value="RO">RO</option>
              <option value="RR">RR</option>
              <option value="SC">SC</option>
              <option value="SP">SP</option>
              <option value="SE">SE</option>
              <option value="TO">TO</option>
            </Form.Select>
          </Form.Group>
        </div>

        <Form.Group className="mb-3">
          <Form.Label className="comic-relief-regular">Email</Form.Label>
          <Form.Control type="email" name="email" id="email" required />
        </Form.Group>

        <Form.Group className="mb-3">
          <Form.Label className="comic-relief-regular">Senha</Form.Label>
          <Form.Control type="password" name="senha" id="senha" required />
        </Form.Group>

        <Form.Group className="mb-3">
          <Form.Label className="comic-relief-regular">
            Confirme sua Senha
          </Form.Label>
          <Form.Control
            type="password"
            name="confsenha"
            id="confsenha"
            required
          />
        </Form.Group>

        <Button variant="success" type="submit" className="w-100">
          Enviar
        </Button>
      </Form>
    </div>
  );
}

export default FormCad;
