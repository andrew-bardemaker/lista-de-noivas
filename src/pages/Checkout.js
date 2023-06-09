
import React from 'react';
import { Link, Redirect } from 'react-router-dom'
import { withStyles } from '@material-ui/core/styles';
import { useStyles } from '../assets/estilos/pages/home';
import {
    Container, Typography, Grid, Snackbar, Button,
    Breadcrumbs, TextField, Backdrop, CircularProgress, Radio,
    RadioGroup, FormControlLabel, FormControl
} from '@material-ui/core';
import { Card, CardContent } from '@material-ui/core';
import MuiAlert from '@material-ui/lab/Alert';
import PropTypes from 'prop-types';
import ArrowBackIcon from '@material-ui/icons/ArrowBack';
import AppMenuLogged from '../components/MenuUser';
import Services from "../Services";
import Footer from '../components/Footer';
import Add from '@material-ui/icons/Add';
import ArrowRightAltIcon from '@material-ui/icons/ArrowRightAlt';
import { createMuiTheme, ThemeProvider } from '@material-ui/core/styles';
import {
    BrowserView,
} from "react-device-detect";
import { If } from 'react-if';
import InputMask from 'react-input-mask';
import LocalOfferIcon from '@material-ui/icons/LocalOffer';

function Alert(props) {
    return <MuiAlert elevation={6} variant="filled" {...props} />;

}

const theme = createMuiTheme({
    palette: {
        primary: {
            light: '#eebf2f',
            main: '#eebf2f',
            dark: '#eebf2f',
            contrastText: '#1f3a4e',
        },
        secondary: {
            light: '#1f3a4e',
            main: '#1f3a4e',
            dark: '#1f3a4e',
            contrastText: '#fff',
        },
    },
});

class Checkout extends React.Component {

    state = {

        openAlert: false,
        alertMessage: '',
        alertStatus: 'info',
        openmodal: false,
        enderecos: [],
        openFormAdress: false,
        id_endereco: '',
        action: "list",
        listCarrinho: [],
        totalCarrinho: 0,
        loadingForm: false,
        openProgress: false,
        redirect: '',
        cartao_venc: '',
        cartao_cvv: '',
        cartao_titular: '',
        cartao_num: '',
        cupom: '',
        descCupom: 0,
        cupomVisible: true,
        cupomText: '',
        original:'',

    }

    componentDidMount() {
        document.getElementById("top").scroll(0, 0);
        let usuario = JSON.parse(localStorage.getItem('user')) || '';
        let token = JSON.parse(localStorage.getItem('token')) || '';
        let carrinho = localStorage.getItem('carrinho') || '';

        if (carrinho === '') {
            this.setState({ redirect: 'home' });
        }

        if (carrinho !== '') {
            const cart = [{
                id_carrinho: carrinho,
                id_produto: "",
                qntd: "",
                action: "list_produtos"
            }];
            Services.carrinho(this, usuario.id, token, cart[0]);
        }

        Services.usuariosEndereco(this, usuario.id, token)
    }

    responseUsuariosEndereco(response) {
        if (response.error === 'true' && response.type !== 'token_invalido') {
            this.setState({ openAlert: true })
            this.setState({ alertMessage: "Opa!" + response.msg })
            this.setState({ alertStatus: 'error' })
            return
        }

        if (response.error === 'true' && response.type === 'token_invalido') {
            localStorage.setItem('token_invalido', 'ok')
            this.setState({ redirect: '#' });
            return
        }

        if (response.success === 'true') {
            this.setState({ enderecos: response.enderecos })
        }
    }

    handleCloseAlert = () => {
        this.setState({ openProgress: false })
        this.setState({ loadingForm: false })
        this.setState({ openAlert: false })
        this.setState({ alertMessage: '' })
        this.setState({ alertStatus: '' })
    }

    responseCarrinho(response) {
        if (response.success === 'true') {
            if (this.state.action === 'criar') {
                localStorage.removeItem('carrinho');
                localStorage.setItem('carrinho', response.id_carrinho);
                return
            }
            if (this.state.action === 'list') {
                this.setState({ listCarrinho: response.produtos })
                this.setState({ totalCarrinho: response.total_carrinho })
                return
            }
            if (this.state.action === 'add') {
                this.setState({ openAlert: true })
                this.setState({ alertMessage: "O produto foi adicionado ao pedido!" })
                this.setState({ alertStatus: 'success' })
                return
            }
            if (this.state.action === 'up') {
                this.setState({ openAlert: true })
                this.setState({ alertMessage: "Quantidade atualizada!" })
                this.setState({ alertStatus: 'success' })
                return
            }

            if (this.state.action === 'add_plus') {
                this.setState({ openAlert: true })
                this.setState({ alertMessage: "Quantidade atualizada!" })
                this.setState({ alertStatus: 'success' })
                return
            }

            if (this.state.action === 'del') {
                this.setState({ openAlert: true })
                this.setState({ alertMessage: "Produto removido com sucesso!" })
                this.setState({ alertStatus: 'success' })
                return
            }
        }
        else {
            this.setState({ openAlert: true })
            this.setState({ alertMessage: "Opa!" + response.msg })
            this.setState({ alertStatus: 'error' })
            return
        }
    }

    handleChangeEndereco = (e) => {
        this.setState({ id_endereco: e.target.value })
    }

    handleInputChange = (event) => {
        if (event.target.name === 'cartao_titular') {
            this.setState({ cartao_titular: event.target.value })
        }
        if (event.target.name === 'cartao_num') {
            this.setState({ cartao_num: event.target.value })
        }
        if (event.target.name === 'cartao_cvv') {
            this.setState({ cartao_cvv: event.target.value })
        }
        if (event.target.name === 'cartao_venc') {
            this.setState({ cartao_venc: event.target.value })
        }
        if (event.target.name === 'mensagem') {
            this.setState({ mensagem: event.target.value })
        }
        if (event.target.name === 'cupom_desconto') {
            this.setState({ cupom: event.target.value })
        }
    }


    handleSubmit = e => {
        this.setState({ openProgress: true })
        this.setState({ loadingForm: true })
        e.preventDefault();

        let usuario = JSON.parse(localStorage.getItem('user')) || '';
        let token = JSON.parse(localStorage.getItem('token')) || '';
        let carrinho = localStorage.getItem('carrinho') || '';
        let endereco = this.state.id_endereco;


        if (endereco === "") {
            this.setState({ openAlert: true })
            this.setState({ alertMessage: "Opa! Selecione o endereço" })
            this.setState({ openProgress: false })
            this.setState({ alertStatus: 'warning' })
            document.getElementById("top").scroll(0, 0);
            return
        }

        if (this.state.cartao_venc.length < 6) {
            this.setState({ openAlert: true })
            this.setState({ alertMessage: "Opa! Informe a data do seu cartão corretamente, o ano deve conter 4 dígitos." })
            this.setState({ alertStatus: 'warning' })
            this.setState({ openProgress: false })
            return
        }

        const values = [{
            cartao_numero: this.state.cartao_num,
            cartao_nome: this.state.cartao_titular,
            cartao_cvv: this.state.cartao_cvv,
            cartao_vencimento: this.state.cartao_venc,
            observacoes: this.state.mensagem,
            cupom: this.state.cupom,


        }];
       
        Services.checkout(this, usuario.id, token, carrinho, endereco, values[0]);
    }

    responseCheckout(response) {
        this.setState({ openProgress: false })
        this.setState({ loadingForm: false })

        if (response.error === 'true' && response.type !== 'token_invalido') {
            this.setState({ openAlert: true })
            this.setState({ alertMessage: "Opa!" + response.msg })
            this.setState({ alertStatus: 'error' })
            this.setState({ openProgress: false })
            return
        }

        if (response.error === 'true' && response.type === 'token_invalido') {
            localStorage.setItem('token_invalido', 'ok')
            this.setState({ redirect: '#' });
            return
        }

        if (response.success === 'true') {
            localStorage.removeItem('carrinho');
            this.setState({ redirect: 'pedidos' })
        }

    }

    confereCupom = () => {
        let ccupom = this.state.cupom;
        let carrinho = this.state.totalCarrinho;

        Services.confereCupom(this, ccupom, carrinho);
    }

    responseConfereCupom(response) {

        if (response.error) {
            this.setState({ openAlert: true })
            this.setState({ alertMessage: "Opa! " + response.msg })
            this.setState({ alertStatus: 'error' })
            this.setState({ openProgress: false })
        }
        else {
            this.setState({ totalCarrinho: response.valorcarrinho })
            this.setState({ original: 'Valor Original: R$ ' + response.original })
            this.setState({ cupomText: 'Cupom ' + this.state.cupom + "(%" + response.porcentagem + ") Aplicado: Valor R$ " + response.desconto })
            this.setState({ openAlert: true })
            this.setState({ alertMessage: "Cupom Aplicado!" })
            this.setState({ alertStatus: 'success' })
            this.setState({ cupomVisible: false })


            console.log(response)
        }

    }

    render() {
        const { classes } = this.props;
        if (this.state.redirect) {
            return (<Redirect to={'/' + this.state.redirect} />);
        }
        return <div className={classes.root} id="top">
            <ThemeProvider theme={theme}>

                <AppMenuLogged />

                <main>
                    <div className={classes.topSpace} />

                    <Container maxWidth="lg" className={classes.container}>
                        <Link to='/sacola'>
                            <Button color="secondary" startIcon={<ArrowBackIcon />} variant='contained'>
                                Voltar
                            </Button>
                        </Link>
                    </Container>

                    <Container maxWidth="lg" className={classes.container}>
                        <Breadcrumbs aria-label="breadcrumb">
                            <Link to='/home' >
                                Home
                            </Link>
                            <Link to="/sacola">
                                Pedido
                            </Link>
                            <Link to="/checkout">
                                Checkout
                            </Link>
                        </Breadcrumbs>
                    </Container>

                    <Container maxWidth="md" className={classes.container}>
                        <Grid container spacing={2}>
                            <Grid item xs={12} sm={12} align="center">
                                <Typography component="h1" variant="h6" align="center" className={classes.title}>
                                    Checkout
                                </Typography>
                            </Grid>

                            <Grid item xs={12} lg={12}>
                                <Card raised={true} className={classes.root} >
                                    <CardContent>
                                        <Typography gutterBottom className="productTitle" variant="h5" component="h3">
                                            Endereços:
                                        </Typography>

                                        <If condition={this.state.enderecos.length > 0}>
                                            <FormControl component="fieldset">
                                                <RadioGroup aria-label="enderecos" name="enderecos" value={this.state.id_endereco} onChange={this.handleChangeEndereco}>
                                                    {this.state.enderecos.map((enderecos) => {
                                                        return <FormControlLabel key={enderecos.id} value={enderecos.id} control={<Radio />} label={enderecos.identificador + " - " + enderecos.endereco + ", " + enderecos.numero} />
                                                    })}
                                                </RadioGroup>
                                            </FormControl>
                                        </If>
                                        <If condition={this.state.enderecos.length === 0}>
                                            <Typography variant="body1" color="secondary" align="center" paragraph>
                                                Não há endereços cadastrados, clique no botão abaixo para cadastrar um novo endereço.
                                            </Typography>
                                        </If>

                                        <Link to={'/enderecocheckout'}><Button
                                            fullWidth
                                            style={{ marginTop: 20 }}
                                            color="secondary"
                                            variant="contained"
                                            endIcon={<Add />}>Cadastrar novo endereço</Button></Link>
                                    </CardContent>
                                </Card>
                            </Grid>

                            <Grid item xs={12} lg={12}>
                                <Card raised={true} className={classes.root} >
                                    <CardContent>
                                        <Typography gutterBottom className="productTitle" variant="h5" component="h3">
                                            Produtos do pedido:
                                        </Typography>
                                        {this.state.listCarrinho.map((produtos) => {
                                            return <Typography variant="body2" color="secondary" component="p" key={produtos.id}>
                                                - {produtos.qntd}X {produtos.titulo}
                                            </Typography>
                                        })}
                                        <Typography variant="body2" color="secondary" component="p" style={{marginTop:'10px',color:"red"}} >
                                            {this.state.original}
                                        </Typography>

                                        <Typography variant="body2" color="secondary" component="p" style={{marginTop:'10px',marginBotton:'10px',color:"green"}}>
                                            {this.state.cupomText}
                                        </Typography>


                                        <Typography variant="body1" color="secondary" align="center" paragraph>
                                            Valor total do pedido: R$ {this.state.totalCarrinho}
                                        </Typography>

                                    </CardContent>
                                </Card>
                            </Grid>

                            <Grid item xs={12} lg={12}>
                                <Card raised={true} className={classes.root} >
                                    <CardContent>
                                        <Typography gutterBottom className="productTitle" variant="h5" component="h3">
                                            Observação ao entregador
                                        </Typography>

                                        <TextField
                                            name="mensagem"
                                            variant="outlined"
                                            required
                                            fullWidth
                                            multiline
                                            rows={4}
                                            id="mensagem"
                                            label="Mensagem"
                                            color="secondary"
                                            onChange={this.handleInputChange}
                                        />
                                    </CardContent>
                                </Card>
                            </Grid>

                            {this.state.cupomVisible && <Grid item xs={12} lg={12} >
                                <Card raised={true} className={classes.root} >
                                    <CardContent>
                                        <Typography gutterBottom className="productTitle" variant="h5" component="h3">
                                            Cupom de Desconto
                                        </Typography>

                                        <TextField
                                            name="cupom_desconto"
                                            variant="outlined"
                                            required

                                            fullWidth
                                            rows={4}
                                            id="cupom_desconto"
                                            label="Cupom de Desconto"
                                            helperText="Insira o código do cupom e clique em 'Inserir Cupom' para validar o desconto."
                                            color="secondary"
                                            onChange={this.handleInputChange}
                                        />

                                        <Button
                                            fullWidth
                                            style={{ marginTop: 20 }}
                                            color="secondary"
                                            variant="contained"
                                            endIcon={<LocalOfferIcon />}
                                            onClick={this.confereCupom}>Inserir Cupom</Button>
                                    </CardContent>
                                </Card>
                            </Grid>}

                            <Grid item xs={12} lg={12}>
                                <Card raised={true} className={classes.root} >
                                    <CardContent>
                                        <Typography gutterBottom className="productTitle" variant="h5" component="h3">
                                            Dados de Pagamento
                                        </Typography>
                                        <Typography gutterBottom color="secondary" variant="subtitle2" component="h2" paragraph>
                                            Apenas cartão de crédito
                                        </Typography>
                                        <Grid container spacing={2} direction="row" justify="center" alignItems="center" >
                                            <Grid item xs={12} sm={12} lg={10}>
                                                <InputMask
                                                    mask="9999999999999999"
                                                    maskChar={null}
                                                    onChange={this.handleInputChange}
                                                >
                                                    {() => <TextField
                                                        name="cartao_num"
                                                        variant="outlined"
                                                        required
                                                        fullWidth
                                                        type="tel"
                                                        label="Número do cartão"
                                                        color="secondary"
                                                    />}
                                                </InputMask>
                                            </Grid>
                                            <Grid item xs={12} sm={12} lg={10}>
                                                <TextField
                                                    name="cartao_titular"
                                                    variant="outlined"
                                                    type="text"
                                                    required
                                                    fullWidth
                                                    label="Titular do cartão"
                                                    color="secondary"
                                                    onChange={this.handleInputChange}
                                                />
                                            </Grid>

                                            <Grid item xs={6} sm={6} lg={5}>
                                                <InputMask
                                                    mask="99/9999"
                                                    maskChar={null}
                                                    onChange={this.handleInputChange}
                                                >
                                                    {() => <TextField
                                                        variant="outlined"
                                                        type="tel"
                                                        required
                                                        fullWidth
                                                        name="cartao_venc"
                                                        label="Vencimento"
                                                        placeholder="MM/AAAA"
                                                        helperText="Formato: MM/AAAA"
                                                        color="secondary"
                                                    />}
                                                </InputMask>
                                            </Grid>
                                            <Grid item xs={6} sm={6} lg={5}>
                                                <InputMask
                                                    mask="9999"
                                                    maskChar={null}
                                                    onChange={this.handleInputChange}
                                                >
                                                    {() => <TextField
                                                        variant="outlined"
                                                        type="tel"
                                                        required
                                                        fullWidth
                                                        label="CVV"
                                                        name="cartao_cvv"
                                                        helperText=" "
                                                        color="secondary"
                                                    />}
                                                </InputMask>
                                            </Grid>
                                        </Grid>

                                    </CardContent>
                                </Card>
                            </Grid>

                            <Grid item xs={12} lg={12}>
                                <Button
                                    color="primary"
                                    variant="contained"
                                    size="large"
                                    className="w-100"
                                    disabled={this.state.loadingForm}
                                    endIcon={<ArrowRightAltIcon />}
                                    onClick={this.handleSubmit}
                                >Finalizar pedido</Button>
                            </Grid>

                        </Grid>
                    </Container>

                    <Snackbar open={this.state.openAlert} autoHideDuration={5000} onClose={this.handleCloseAlert}>
                        <Alert severity={this.state.alertStatus} onClose={this.handleCloseAlert}>
                            {this.state.alertMessage}
                        </Alert>
                    </Snackbar>

                    <Backdrop className={classes.backdrop} open={this.state.openProgress}>
                        <CircularProgress color="inherit" />
                    </Backdrop>

                </main>

                <BrowserView>
                    <Footer />
                </BrowserView>
            </ThemeProvider>
        </div >
    }
}

Checkout.propTypes = {
    classes: PropTypes.object.isRequired,
};

export default withStyles(useStyles)(Checkout);