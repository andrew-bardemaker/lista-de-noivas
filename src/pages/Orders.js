
import React from 'react';
import { Link, Redirect } from 'react-router-dom'
import { withStyles } from '@material-ui/core/styles';
import { useStyles } from '../assets/estilos/pages/home';
import {
  Container, Typography, Grid, Snackbar,
  Button, Slide, Breadcrumbs, Badge, LinearProgress
} from '@material-ui/core';
import { Card, CardActionArea, CardActions, CardContent, CardMedia } from '@material-ui/core';
import MuiAlert from '@material-ui/lab/Alert';
import PropTypes from 'prop-types';
import ArrowBackIcon from '@material-ui/icons/ArrowBack';
import AppMenuLogged from '../components/MenuUser';
import Services from "../Services";
import Footer from '../components/Footer';
import Add from '@material-ui/icons/Add';
import CachedIcon from '@material-ui/icons/Cached';
import ReactTimeout from 'react-timeout';
import { createMuiTheme, ThemeProvider } from '@material-ui/core/styles';
import {
  BrowserView,
} from "react-device-detect";
import { If } from 'react-if';

function Alert(props) {
  return <MuiAlert elevation={6} variant="filled" {...props} />;
}

const Transition = React.forwardRef(function Transition(props, ref) {
  return <Slide direction="up" ref={ref} {...props} />;
});


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

class Orders extends React.Component {

  state = {

    openAlert: false,
    alertMessage: '',
    alertStatus: 'info',
    openmodal: false,
    pedidos: [],
    redirect: '',
  }

  componentDidMount() {
    let usuario = JSON.parse(localStorage.getItem('user')) || '';
    let token = JSON.parse(localStorage.getItem('token')) || '';

    Services.pedidos(this, usuario.id, token)
  }

  componentWillUnmount(){
    this.props.clearInterval(this.reload);
  }

  responsePedidos(response) {

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
      this.reload = this.props.setInterval(this.reload, 120000)
      this.setState({ pedidos: response.pedidos })
    }
  }

  reload = () => {
    window.location.reload();
  }

  handleCloseAlert = () => {
    this.setState({ openAlert: false })
    this.setState({ alertMessage: '' })
    this.setState({ alertStatus: '' })
  }

  cancelOrderConfirm = () => {
    let usuario = JSON.parse(localStorage.getItem('user')) || '';
    let token = JSON.parse(localStorage.getItem('token')) || '';
    let deleteAdress = this.state.deleteAdress
    Services.deleteEndereco(this, usuario.id, token, deleteAdress)
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
            <Link to='/home'>
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
              <Link to="/pedidos">
                Meus Pedidos
                            </Link>
            </Breadcrumbs>
          </Container>

          <Container maxWidth="md" className={classes.container}>
            <Grid container spacing={2}>
              <Grid item xs={12} sm={12} align="center">
                <Typography component="h1" variant="h6" align="center" className={classes.title}>
                  Meus Pedidos
                                    </Typography>
              </Grid>

              <Grid item lg={4}></Grid>

              <Grid item xs={12} lg={4}>
                <Link to={'/produtos'}><Button
                  color="primary"
                  variant="contained"
                  className="w-100"
                  endIcon={<Add />}>Novo pedido</Button></Link>
              </Grid>

              <Grid item lg={4}></Grid>

              <Grid item xs={false} lg={4}></Grid>
              <Grid item xs={12} lg={4}>
                <Button
                  color="secondary"
                  variant="contained"
                  fullWidth
                  endIcon={<CachedIcon />}
                  onClick={() => window.location.reload()}
                >Atualizar a página</Button></Grid>
              <Grid item xs={false} lg={4}></Grid>

            
              {this.state.pedidos.map((pedidos) => {
                return <Grid item xs={12} md={12} key={pedidos.id}>
                  <Link to={"/pedido/" + pedidos.id}><Card raised={true} className={classes.root} >
                  <CardActionArea>
                    <CardContent>
                      <If condition={pedidos.status !== 5 && pedidos.status !== '5'}>
                        <LinearProgress color="primary" variant="determinate" value={parseInt(pedidos.status) * 25} />
                      </If>
                      <If condition={pedidos.status === 5 || pedidos.status === '5'}>
                        <Badge color="error" variant="dot">
                        </Badge></If>
                      <Typography gutterBottom variant="h5" component="h2" color="secondary">
                        Status do pedido: {pedidos.status_titulo}
                       
                      </Typography>

                      <If condition={pedidos.status === 1 || pedidos.status === '1'}>
                      <Typography gutterBottom component="subtitle1" color="primary">
                        Teu pedido está sendo direcionado para o nosso carro mais próximo, aguarde...
                      </Typography>
                      </If>
                      <If condition={pedidos.status === 2 || pedidos.status === '2' || pedidos.status === 3 || pedidos.status === '3'}>
                      <Typography gutterBottom component="subtitle1" color="primary">
                        Teu pedido vai chegar em breve, um entregador Gelada em Casa já está a caminho!
                      </Typography>
                      </If>
                      
                      <Typography variant="body2" color="secondary" component="p">
                        Pedido nº: {pedidos.id}
                      </Typography>
                      <Typography variant="body2" color="secondary" component="p">
                        Data do pedido: {pedidos.data_hora_registro}
                      </Typography>
                      <Typography variant="body2" color="secondary" component="p">
                        Itens: {pedidos.produtos}
                      </Typography>
                      <Typography variant="body2" color="secondary" component="p">
                        Valor total: {pedidos.total_pedido}
                      </Typography>
                    </CardContent>
                  </CardActionArea>
                  <CardActions>
                    <Link to={"/pedido/" + pedidos.id}><Button variant="contained" size="medium" color="primary">
                      Detalhes do pedido
                      </Button></Link>
                  </CardActions>
                </Card></Link></Grid>
              })}

            </Grid>
          </Container>

          <Snackbar open={this.state.openAlert} autoHideDuration={5000} onClose={this.handleCloseAlert}>
            <Alert severity={this.state.alertStatus} onClose={this.handleCloseAlert}>
              {this.state.alertMessage}
            </Alert>
          </Snackbar>
        </main>

        <BrowserView>
          <Footer />
        </BrowserView>
      </ThemeProvider>
    </div >
  }
}

Orders.propTypes = {
  classes: PropTypes.object.isRequired,
};

export default ReactTimeout(withStyles(useStyles)(Orders));