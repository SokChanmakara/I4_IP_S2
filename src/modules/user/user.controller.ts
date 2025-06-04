import {
  Get,
  Param,
  Controller,
  Post,
  Body,
  Patch,
  Delete,
  UsePipes,
  ValidationPipe,
} from '@nestjs/common';
import { UsersService } from './user.service';
import { User } from './user.entity'; // optional, for typing
import { CreateUserDto } from './dto/create-user.dto';

@Controller('users')
export class UsersController {
  constructor(private readonly userService: UsersService) {}

  @Get()
  findAll() {
    return this.userService.findAll();
  }

  @Get(':id')
  findOne(@Param('id') id: string) {
    return this.userService.findOne(Number(id));
  }

  @Post()
  @UsePipes(new ValidationPipe({whitelist:true}))
  create(@Body() body: Partial<CreateUserDto>) {
    return this.userService.create(body);
  }

  @Patch(':id')
  update(@Param('id') id: string, @Body() body: Partial<User>) {
    return this.userService.update(Number(id), body);
  }

  @Delete(':id')
  remove(@Param('id') id: string) {
    return this.userService.remove(Number(id));
  }
}
