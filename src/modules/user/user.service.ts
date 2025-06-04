import { Injectable, NotFoundException } from '@nestjs/common';
import { InjectRepository } from '@nestjs/typeorm';
import { Not, Repository } from 'typeorm';
import { User } from './user.entity'; 

@Injectable()
export class UsersService {
  constructor(
    @InjectRepository(User)
    private readonly usersRepo: Repository<User>,
  ) {}

  async create(userData: Partial<User>) {
    const user = this.usersRepo.create(userData);
    return await this.usersRepo.save(user);
  }

  async findAll() {
    return await this.usersRepo.find({ relations: ['tasks'] });
  }

  async findOne(id: number) {
    const user = await this.usersRepo.findOne({ where: { id }, relations: ['tasks'] });
    if (!user) {
      throw new NotFoundException(`User with ID ${id} not found`);
    }
    return user;
  }

  async update(id: number, updateData: Partial<User>) {
    const user = await this.findOne(id); 
    Object.assign(user, updateData);
    return await this.usersRepo.save(user);
  }

  async remove(id: number) {
    const result = await this.usersRepo.delete(id);
    if (result.affected === 0) {
      throw new NotFoundException(`User with ID ${id} not found`);
    }
    return { message: 'User deleted successfully' };
  }

}
